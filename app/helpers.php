<?php
use Carbon\Carbon;

// 数値のフォーマット（カンマ区切り）
if (!function_exists('format_number')) {
    function format_number($number)
    {
        return number_format($number);
    }
}

// 日付のフォーマット
if (!function_exists('format_date')) {
    function format_date($date)
    {
        return Carbon::parse($date)->format('Y年m月d日');
    }
}

// 時間のフォーマット
if (!function_exists('format_time')) {
    function format_time($time)
    {
        return Carbon::parse($time)->format('H:i');
    }
}

// 日付と時間のフォーマット
if (!function_exists('format_datetime')) {
    function format_datetime($datetime)
    {
        return Carbon::parse($datetime)->format('Y年m月d日 H:i');
    }
}

// 講義時間の計算
if (!function_exists('get_duration_time')) {
    function get_duration_time($start_time, $end_time)
    {
        $start = Carbon::parse($start_time);
        $end = Carbon::parse($end_time);
        return $start->diffInMinutes($end);
    }
}
