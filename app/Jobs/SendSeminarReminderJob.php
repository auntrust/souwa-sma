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
use Illuminate\Support\Facades\Mail;

class SendSeminarReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        // 明日開催される現地セミナーを取得
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $seminars = Seminar::where('is_active', true)->where('seminar_type', 'onsite')->where('onsite_date', $tomorrow)->get();

        foreach ($seminars as $seminar) {
            // セミナーの参加者を取得
            $participants = SeminarCustomer::where('seminar_id', $seminar->id)->get();

            foreach ($participants as $participant) {
                // リマインダーメールを送信
                Mail::to($participant->email)->send(new SeminarReminderMail($seminar, $participant));
            }
        }
    }
}
