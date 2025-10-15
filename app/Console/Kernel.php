<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * スケジュールの定義
     */
    protected function schedule(Schedule $schedule): void
    {
        // routes/console.phpでスケジュール管理
    }

    /**
     * コマンド登録
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
