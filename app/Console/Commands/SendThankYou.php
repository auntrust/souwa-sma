<?php

namespace App\Console\Commands;

use App\Jobs\SendThankYouJob;
use Illuminate\Console\Command;

class SendThankYou extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seminar:send-thank-you';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'セミナー参加のお礼メールを送信する';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        SendThankYouJob::dispatch();

        $this->info('セミナー参加のお礼メールの送信ジョブをキューに追加しました。');

        return 0;
    }
}
