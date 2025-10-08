<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendBulkMailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:send-bulk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '一斉メール送信コマンド';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customers = Customer::where('is_active', true)->get();

        foreach ($customers as $customer) {
            Mail::to($customer->email)->send(new SeminarEntryNotification($customer));
        }

        $this->info('メール送信完了');
    }
}
