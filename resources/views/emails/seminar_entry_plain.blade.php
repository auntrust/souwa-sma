{{ $request->name }} 様

この度は「{{ $seminar->name }}」にお申込みいただき、
誠にありがとうございます。

お申込み内容を下記の通り受付いたしました。

=================================
 お申込みいただいたセミナー
=================================

▼セミナー名
{{ $seminar->name }}

▼開催日
{{ format_date($seminar->seminar_date) }}

▼講義時間
{{ format_time($seminar->start_time) }}〜{{ format_time($seminar->end_time) }}（{{ get_duration_time($seminar->start_time, $seminar->end_time) }}分）

▼受講料
{{ $seminar->is_paid == '1' ? $seminar->paid_fee->toLocaleString() . '円' : '無料' }}

@if ($seminar->seminar_type == 'onsite')
▼開催形式
現地開催

▼会場名
{{ $seminar->venue_name }}

▼郵便番号
{{ $seminar->venue_zip }}

▼住所
{{ $seminar->venue_address }}{{ $seminar->venue_building }}

▼電話番号
{{ $seminar->venue_tel }}

▼地図URL
{{ $seminar->venue_map_url }}

@elseif ($seminar->seminar_type == 'online')
▼開催形式
オンラインセミナー

▼URL
{{ $seminar->online_url }}

▼ID
{{ $seminar->online_id }}

▼PWD
{{ $seminar->online_pwd }}

@elseif ($seminar->seminar_type == 'webinar')
▼開催形式
ウェビナー

▼視聴URL
{{ $seminar->webinar_url }}

▼視聴可能期間
{{ format_datetime($seminar->webinar_start_at) }}〜{{ format_datetime($seminar->webinar_end_at) }}
@endif

@if ($seminar->seminar_type == 'online')
※開催時刻になりましたら上記のURLよりご参加ください。
@endif
@if ($seminar->seminar_type == 'webinar')
※開催時刻になりましたら上記のURLよりご参加ください。
※視聴は1回のみとなります。
@endif

=================================
 お客様情報
=================================

@if ($request->name)
▼氏名
{{ $request->name }}
@endif

@if ($request->furigana)
▼ふりがな
{{ $request->furigana }}
@endif

@if ($request->tel)
▼電話番号
{{ $request->tel }}
@endif

@if ($request->email)
▼メールアドレス
{{ $request->email }}
@endif

@if ($request->todofuken)
▼都道府県
{{ $request->todofuken }}
@endif

=================================
 会社情報
=================================

@if ($request->co_name)
▼会社名
{{ $request->co_name }}
@endif

@if ($request->co_tel)
▼電話番号
{{ $request->co_tel }}
@endif

@if ($request->co_busho)
▼部署
{{ $request->co_busho }}
@endif

@if ($request->co_post)
▼役職
{{ $request->co_post }}
@endif

=================================
 その他
=================================

@if ($request->applicant_count)
▼参加人数
{{ $request->applicant_count }} 名
@endif

@if ($request->request)
▼ご質問・要望
{!! nl2br(e($request->get('request'))) !!}
@endif

後日、詳細のご案内をメールにてお送りいたします。
ご不明点等ございましたら、お気軽にお問い合わせください。
今後ともよろしくお願いいたします。
