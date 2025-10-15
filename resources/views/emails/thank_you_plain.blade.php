{{ $participant->name }} 様

いつもお世話になっております。

「{{ $seminar->name }}」にご参加いただき、
誠にありがとうございました。心より御礼申し上げます。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ セミナーについて
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

【セミナー名】
{{ $seminar->name }}

@if ($seminar->seminar_type == 'onsite')
【開催日時】
{{ format_date($seminar->onsite_date) }}（{{ \Carbon\Carbon::parse($seminar->onsite_date)->isoFormat('dddd') }}）
{{ format_time($seminar->onsite_start_time) }}〜{{ format_time($seminar->onsite_end_time) }}

【会場】
{{ $seminar->onsite_name }}

@elseif ($seminar->seminar_type == 'online')
【開催日時】
{{ format_date($seminar->online_date) }}（{{ \Carbon\Carbon::parse($seminar->online_date)->isoFormat('dddd') }}）
{{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}

【開催形式】
オンライン開催

@elseif ($seminar->seminar_type == 'webinar')
【視聴期間】
{{ format_datetime($seminar->webinar_start_at) }}
〜{{ format_datetime($seminar->webinar_end_at) }}

【開催形式】
ウェビナー配信

@endif
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ アンケートご協力のお願い
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
より良いセミナーを開催するため、簡単なアンケートにご協力をお願いいたします。
お忙しい中恐縮ですが、忌憚のないご意見をお聞かせください。

【アンケートURL】
{{ url('/feedback/' . $seminar->unique_key . '/' . $participant->unique_key) }}

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 今後のセミナー情報について
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
今後も皆様のお役に立てる様々なテーマでセミナーを開催してまいります。
今後のセミナー案内は、ご登録いただいたメールアドレス宛に
お送りさせていただきますので、ご安心ください。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お問い合わせについて
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
本日のセミナー内容に関するご質問や、
今後のセミナーに関するお問い合わせがございましたら、
お気軽に下記お問い合わせ窓口までご連絡ください。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ ご参加者情報（確認用）
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if ($participant->name)
氏名　　　：{{ $participant->name }}
@endif
@if ($participant->furigana)
ふりがな　：{{ $participant->furigana }}
@endif
@if ($participant->email)
メール　　：{{ $participant->email }}
@endif
@if ($participant->tel)
電話番号　：{{ $participant->tel }}
@endif
@if ($participant->co_name)
ご所属　　：{{ $participant->co_name }}
@endif
@if ($participant->co_busho)
部署　　　：{{ $participant->co_busho }}
@endif
@if ($participant->co_post)
役職　　　：{{ $participant->co_post }}
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 最後に
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
本日はお忙しい中、貴重なお時間をいただき誠にありがとうございました。
今回のセミナーが皆様のお役に立てれば幸いです。

今後ともどうぞよろしくお願いいたします。

@include('emails.partials.footer')