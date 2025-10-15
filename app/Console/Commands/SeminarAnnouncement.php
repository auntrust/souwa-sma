<?php

namespace App\Console\Commands;

use App\Jobs\SendSeminarAnnouncementJob;
use Illuminate\Console\Command;

class SeminarAnnouncement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seminar:seminar-announcement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'セミナ案内メールを送信する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendSeminarAnnouncementJob::dispatch();

        $this->info('セミナ案内メールの送信ジョブをキューに追加しました。');

        return 0;
    }
}
