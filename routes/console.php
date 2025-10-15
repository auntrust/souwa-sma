<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// リマインダーメールのスケジュール
Schedule::command('seminar:send-reminders')->dailyAt('09:00')->timezone('Asia/Tokyo')->description('リマインダーメールを送信');

// リマインダーメールのスケジュール
Schedule::command('seminar:send-thank-you')->dailyAt('20:00')->timezone('Asia/Tokyo')->description('お礼メールを送信');
