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

// 郵便番号のフォーマット
if (!function_exists('format_postal_code')) {
    function format_postal_code($postal_code)
    {
        // 数字以外を除去
        $code = preg_replace('/[^\d]/', '', (string) $postal_code);
        // 7桁になるよう左側を0で埋める
        $code = str_pad($code, 7, '0', STR_PAD_LEFT);

        // 7桁でない場合は元の値をそのまま返す
        if (strlen($code) !== 7) {
            return (string) $postal_code;
        }

        // 〒XXX-XXXX の形式で返す
        return '〒' . substr($code, 0, 3) . '-' . substr($code, 3);
    }
}
