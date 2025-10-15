<?php

namespace App\Jobs;

use App\Mail\SeminarReminderMail;
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

class SendSeminarReminderJob implements ShouldQueue
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
            // 明日の日付を取得
            $tomorrow = Carbon::tomorrow(config('app.timezone'))->format('Y-m-d');
            $tomorrowDateTime = Carbon::tomorrow(config('app.timezone'));

            // セミナータイプ別に明日開催されるセミナーを取得
            $seminars = Seminar::with(['seminarCustomers.customer'])
                ->where('is_active', true)
                ->where(function ($query) use ($tomorrow, $tomorrowDateTime) {
                    // onsite セミナー: onsite_date が明日
                    $query
                        ->where(function ($q) use ($tomorrow) {
                            $q->where('seminar_type', 'onsite')->where('onsite_date', $tomorrow);
                        })
                        // online セミナー: online_date が明日
                        ->orWhere(function ($q) use ($tomorrow) {
                            $q->where('seminar_type', 'online')->where('online_date', $tomorrow);
                        })
                        // webinar セミナー: webinar_start_at が明日
                        ->orWhere(function ($q) use ($tomorrowDateTime) {
                            $q->where('seminar_type', 'webinar')->whereDate('webinar_start_at', $tomorrowDateTime->format('Y-m-d'));
                        });
                })
                ->get();

            $totalSent = 0;
            $errors = [];

            foreach ($seminars as $seminar) {
                foreach ($seminar->seminarCustomers as $participant) {
                    $customer = $participant->customer;

                    // 顧客データとメールアドレスの検証
                    if (!$customer || !$customer->email || !filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
                        $errors[] = "Invalid customer data or email for participant ID: {$participant->id}";
                        continue;
                    }

                    try {
                        // メール送信（SeminarReminderMailのコンストラクタに合わせて調整）
                        Mail::to($customer->email)->send(new SeminarReminderMail($seminar, $participant));
                        $totalSent++;

                        // 送信成功をログに記録
                        Log::info("Seminar reminder sent to {$customer->email} for seminar: {$seminar->name} (type: {$seminar->seminar_type})");
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send reminder to {$customer->email}: {$e->getMessage()}";
                        Log::warning("Failed to send reminder to {$customer->email}: {$e->getMessage()}");
                    }
                }
            }

            // 結果をログに記録
            Log::info("SendSeminarReminderJob completed. Sent: {$totalSent}, Errors: " . count($errors));

            if (!empty($errors)) {
                Log::warning('SendSeminarReminderJob errors: ' . implode('; ', $errors));
            }
        } catch (\Exception $e) {
            Log::error('SendSeminarReminderJob failed: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendSeminarReminderJob permanently failed: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
