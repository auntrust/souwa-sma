<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * スケジュールの定義
     */
    protected function schedule(Schedule $schedule)
    {
        // 毎日午前8時に一斉メール送信コマンドを実行
        $schedule->command('mail:send-bulk')->dailyAt('8:00');
    }

    /**
     * コマンド登録
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
