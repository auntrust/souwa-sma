<?php

namespace App\Jobs;

use App\Mail\SeminarSeminarAnnouncementMail;
use App\Models\EmailLog;
use App\Models\Seminar;
use App\Models\SeminarCustomer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class SendSeminarAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     *
     * @var int
     */
    public $retryAfter = 300; // 5分後にリトライ

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // 今日の日付を取得
            $today = Carbon::today(config('app.timezone'));

            // 案内送信対象日（3日後、5日後、7日後、14日後）
            $targetDates = [$today->copy()->addDays(3)->format('Y-m-d'), $today->copy()->addDays(5)->format('Y-m-d'), $today->copy()->addDays(7)->format('Y-m-d'), $today->copy()->addDays(14)->format('Y-m-d')];

            // セミナータイプ別に対象日に開催されるセミナーを取得
            $seminars = Seminar::with([
                'seminarCustomers.customer' => function ($query) {
                    $query->where('is_unsubscribe', 0);
                },
            ])
                ->where('is_active', true)
                ->where(function ($query) use ($targetDates) {
                    // onsite セミナー
                    $query
                        ->where(function ($q) use ($targetDates) {
                            $q->where('seminar_type', 'onsite')->whereIn('onsite_date', $targetDates);
                        })
                        // online セミナー
                        ->orWhere(function ($q) use ($targetDates) {
                            $q->where('seminar_type', 'online')->whereIn('online_date', $targetDates);
                        })
                        // webinar セミナー
                        ->orWhere(function ($q) use ($targetDates) {
                            $q->where('seminar_type', 'webinar')->where(function ($subQ) use ($targetDates) {
                                foreach ($targetDates as $targetDate) {
                                    $subQ->orWhereDate('webinar_start_at', $targetDate);
                                }
                            });
                        });
                })
                ->get();

            $totalSent = 0;
            $errors = [];

            foreach ($seminars as $seminar) {
                // セミナーの開催日を取得
                $seminarDate = $this->getSeminarDate($seminar);
                if (!$seminarDate) {
                    $errors[] = "Could not determine seminar date for seminar ID: {$seminar->id}";
                    continue;
                }

                // 開催日までの日数を計算
                $daysUntilSeminar = $today->diffInDays($seminarDate, false);

                // 送信対象日かチェック（3日前、5日前、7日前、14日前）
                if (!in_array($daysUntilSeminar, [3, 5, 7, 14])) {
                    continue;
                }

                foreach ($seminar->seminarCustomers as $participant) {
                    $customer = $participant->customer;

                    // 顧客データとメールアドレスの検証
                    if (!$customer || !$customer->email || !filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Invalid customer data or email for participant ID: {$participant->id}";
                        continue;
                    }

                    // すでに参加している顧客かチェック
                    if (DB::table('seminar_customers')->where('seminar_id', $seminar->id)->where('customer_id', $customer->id)->exists()) {
                        continue; // 参加している場合はスキップ
                    }

                    // メール配信ログの作成（送信前）
                    $emailLog = EmailLog::create([
                        'mail_type' => "{$daysUntilSeminar}日前の案内",
                        'recipient_email' => $customer->email,
                        'recipient_name' => $customer->name,
                        'seminar_id' => $seminar->id,
                        'customer_id' => $customer->id,
                        'seminar_customer_id' => $participant->id,
                        'status' => 'pending',
                        'sent_at' => null,
                    ]);

                    try {
                        // セミナー案内メール送信
                        Mail::to($customer->email)->send(new SeminarSeminarAnnouncementMail($seminar, $participant));
                        $totalSent++;

                        // 配信ログを成功に更新
                        $emailLog->update([
                            'status' => 'success',
                            'sent_at' => Carbon::now(),
                        ]);
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send announcement email to {$customer->email}: {$e->getMessage()}";

                        // 配信ログを失敗に更新
                        $emailLog->update([
                            'status' => 'failed',
                            'sent_at' => Carbon::now(),
                            'error_message' => $e->getMessage(),
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * セミナーの開催日を取得
     */
    private function getSeminarDate(Seminar $seminar): ?Carbon
    {
        switch ($seminar->seminar_type) {
            case 'onsite':
                return $seminar->onsite_date ? Carbon::parse($seminar->onsite_date) : null;
            case 'online':
                return $seminar->online_date ? Carbon::parse($seminar->online_date) : null;
            case 'webinar':
                return $seminar->webinar_start_at ? Carbon::parse($seminar->webinar_start_at) : null;
            default:
                return null;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        // Job失敗時の処理が必要な場合はここに記述
    }
}
