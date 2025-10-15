<?php

namespace App\Jobs;

use App\Mail\SeminarThankYouMail;
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

class SendThankYouJob implements ShouldQueue
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
            // 今日の日付を取得（セミナー開催日当日の夜に送信）
            $today = Carbon::today(config('app.timezone'))->format('Y-m-d');
            $todayDateTime = Carbon::today(config('app.timezone'));

            // セミナータイプ別に今日開催されたセミナーを取得
            $seminars = Seminar::with(['seminarCustomers.customer'])
                ->where('is_active', true)
                ->where(function ($query) use ($today, $todayDateTime) {
                    // onsite セミナー: onsite_date が今日
                    $query
                        ->where(function ($q) use ($today) {
                            $q->where('seminar_type', 'onsite')->where('onsite_date', $today);
                        })
                        // online セミナー: online_date が今日
                        ->orWhere(function ($q) use ($today) {
                            $q->where('seminar_type', 'online')->where('online_date', $today);
                        })
                        // webinar セミナー: webinar_start_at が今日
                        ->orWhere(function ($q) use ($todayDateTime) {
                            $q->where('seminar_type', 'webinar')->whereDate('webinar_start_at', $todayDateTime->format('Y-m-d'));
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
                        // お礼メール送信
                        Mail::to($customer->email)->send(new SeminarThankYouMail($seminar, $participant));
                        $totalSent++;

                        // 送信成功をログに記録
                        Log::info("Seminar thank you email sent to {$customer->email} for seminar: {$seminar->name} (type: {$seminar->seminar_type})");
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send thank you email to {$customer->email}: {$e->getMessage()}";
                        Log::warning("Failed to send thank you email to {$customer->email}: {$e->getMessage()}");
                    }
                }
            }

            // 結果をログに記録
            Log::info("SendThankYouJob completed. Sent: {$totalSent}, Errors: " . count($errors));

            if (!empty($errors)) {
                Log::warning('SendThankYouJob errors: ' . implode('; ', $errors));
            }
        } catch (\Exception $e) {
            Log::error('SendThankYouJob failed: ' . $e->getMessage(), [
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
        Log::error('SendThankYouJob permanently failed: ' . $exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);
    }
}
