<?php

namespace App\Jobs;

use App\Mail\SeminarSeminarAnnouncementMail;
use App\Models\Seminar;
use App\Models\SeminarCustomer;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            $seminars = Seminar::with(['seminarCustomers.customer'])
                ->where('is_active', true)
                ->where(function ($query) use ($targetDates, $today) {
                    foreach ($targetDates as $targetDate) {
                        // onsite セミナー: onsite_date が対象日
                        $query
                            ->orWhere(function ($q) use ($targetDate) {
                                $q->where('seminar_type', 'onsite')->where('onsite_date', $targetDate);
                            })
                            // online セミナー: online_date が対象日
                            ->orWhere(function ($q) use ($targetDate) {
                                $q->where('seminar_type', 'online')->where('online_date', $targetDate);
                            });
                    }

                    // webinar セミナー: webinar_start_at が対象日のいずれか
                    foreach ($targetDates as $targetDate) {
                        $query->orWhere(function ($q) use ($targetDate) {
                            $q->where('seminar_type', 'webinar')->whereDate('webinar_start_at', $targetDate);
                        });
                    }
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

                    try {
                        // セミナー案内メール送信
                        Mail::to($customer->email)->send(new SeminarSeminarAnnouncementMail($seminar, $participant));
                        $totalSent++;

                        // 送信成功をログに記録
                        Log::info("Seminar announcement email sent to {$customer->email} for seminar: {$seminar->name} (type: {$seminar->seminar_type}, {$daysUntilSeminar} days before)");
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send announcement email to {$customer->email}: {$e->getMessage()}";
                        Log::warning("Failed to send announcement email to {$customer->email}: {$e->getMessage()}");
                    }
                }
            }

            // 結果をログに記録
            Log::info("SendSeminarAnnouncementJob completed. Sent: {$totalSent}, Errors: " . count($errors));

            if (!empty($errors)) {
                Log::warning('SendSeminarAnnouncementJob errors: ' . implode('; ', $errors));
            }
        } catch (\Exception $e) {
            Log::error('SendSeminarAnnouncementJob failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
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
        Log::error('SendSeminarAnnouncementJob permanently failed: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
