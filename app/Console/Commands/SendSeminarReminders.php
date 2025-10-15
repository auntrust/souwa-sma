<?php

namespace App\Console\Commands;

use App\Jobs\SendSeminarReminderJob;
use Illuminate\Console\Command;

class SendSeminarReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seminar:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'リマインダーメールを送信する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendSeminarReminderJob::dispatch();

        $this->info('リマインダーメールの送信ジョブをキューに追加しました。');

        return 0;
    }
}
