<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// セミナーリマインダーのスケジュール
Schedule::command('seminar:send-reminders')->dailyAt('11:00')->timezone('Asia/Tokyo')->description('セミナーリマインダーメールを送信');
