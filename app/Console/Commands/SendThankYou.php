<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendThankYou extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-thank-you';

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
        //
    }
}
