{{ $customer->name }} 様

この度は「{{ $seminar->name }}」にお申込みいただき、
誠にありがとうございます。

お申込み内容を下記の通り受付いたしました。

=================================
 お申込みいただいたセミナー
=================================

▼セミナー名
{{ $seminar->name }}

@if ($seminar->seminar_type == 'onsite')
▼開催日
{{ format_date($seminar->onsite_date) }}

▼講義時間
{{ format_time($seminar->onsite_start_time) }}〜{{ format_time($seminar->onsite_end_time) }}（{{ get_duration_time($seminar->onsite_start_time, $seminar->onsite_end_time) }}分）

▼会場名
{{ $seminar->onsite_name }}

▼郵便番号
{{ format_postal_code($seminar->onsite_zip) }}

▼住所
{{ $seminar->onsite_address }}
{{ $seminar->onsite_building }}

▼地図URL
{{ $seminar->onsite_map_url }}

@elseif ($seminar->seminar_type == 'online')
▼開催日
{{ format_date($seminar->online_date) }}

▼講義時間
{{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}（{{ get_duration_time($seminar->online_start_time, $seminar->online_end_time) }}分）

▼URL
{{ $seminar->online_url }}

▼ミーティングID
{{ $seminar->online_id }}

▼パスコード
{{ $seminar->online_pwd }}

@elseif ($seminar->seminar_type == 'webinar')
▼視聴URL
{{ $seminar->webinar_url }}

▼視聴可能期間
{{ format_datetime($seminar->webinar_start_at) }}〜{{ format_datetime($seminar->webinar_end_at) }}

@endif
▼受講料
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円' : '無料' }}

@if ($seminar->seminar_type == 'online')
※開催時刻になりましたら上記のURLよりご参加ください。
@endif
@if ($seminar->seminar_type == 'webinar')
※開催時刻になりましたら上記のURLよりご参加ください。
※視聴は1回のみとなります。
@endif
