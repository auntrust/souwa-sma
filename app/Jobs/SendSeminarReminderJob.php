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
            // 明日開催される現地セミナーを取得
            $tomorrow = Carbon::tomorrow()->format('Y-m-d');

            $seminars = Seminar::where('is_active', true)->where('seminar_type', 'onsite')->where('onsite_date', $tomorrow)->get();

            foreach ($seminars as $seminar) {
                // セミナーの参加者を取得（Customerリレーションも含める）
                $participants = SeminarCustomer::with('customer')->where('seminar_id', $seminar->id)->get();

                foreach ($participants as $participant) {
                    $customer = $participant->customer;

                    // メールアドレスが存在し、顧客データが存在する場合のみリマインダーメールを送信
                    if ($customer && $customer->email && filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
                        try {
                            Mail::to($customer->email)->send(new SeminarReminderMail($seminar, $customer));
                            Log::info("Reminder email sent to {$customer->email} for seminar {$seminar->name}");
                        } catch (\Exception $e) {
                            Log::error("Failed to send reminder email to {$customer->email}: " . $e->getMessage());
                            throw $e; // ジョブを失敗させる
                        }
                    } else {
                        Log::warning("Invalid customer data or email for participant ID: {$participant->id}");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('SendSeminarReminderJob failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SendSeminarReminderJob permanently failed: ' . $exception->getMessage());
    }
}
