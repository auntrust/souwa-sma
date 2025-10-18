{{ $customer->name }} 様

いつもお世話になっております。

この度、下記の通りセミナーを開催いたします。

皆様のお役に立てる内容となっておりますので、
もしよろしければぜひご参加ください。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ セミナー詳細
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

【セミナー名】
{{ $seminar->name }}

@if (!empty($seminar->description))
【セミナー内容】
{{ $seminar->description }}

@endif
@if ($seminar->seminar_type == 'onsite')
【開催日時】
{{ format_date($seminar->onsite_date) }}（{{ \Carbon\Carbon::parse($seminar->onsite_date)->isoFormat('dddd') }}）
{{ format_time($seminar->onsite_start_time) }}〜{{ format_time($seminar->onsite_end_time) }}

【会場】
{{ $seminar->onsite_name }}

【定員】
{{ $seminar->onsite_capacity }}人

【受講料】
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円（税込）' : '無料' }}
@elseif ($seminar->seminar_type == 'online')
【開催日時】
{{ format_date($seminar->online_date) }}（{{ \Carbon\Carbon::parse($seminar->online_date)->isoFormat('dddd') }}）
{{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}

【開催形式】
オンライン開催（Zoom等）

【定員】
{{ $seminar->online_capacity }}人

【受講料】
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円（税込）' : '無料' }}
@elseif ($seminar->seminar_type == 'webinar')
【視聴期間】
{{ format_datetime($seminar->webinar_start_at) }}
〜{{ format_datetime($seminar->webinar_end_at) }}

【開催形式】
ウェビナー配信

【視聴料】
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円（税込）' : '無料' }}
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お申し込み方法
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

参加をご希望の方は、下記の方法でお申し込みください。
セミナーの詳細についても下記ページよりご確認いただけます。

【お申し込みページ】
@if (!empty($seminar->detail_url))
{{ $seminar->detail_url . '?sma_c=' . $customer->unique_key }}
@else
{{ url('/entry/' . $seminar->unique_key . '/' . $customer->unique_key) }}
@endif

【お申し込み期限】
@if ($seminar->seminar_type == 'onsite')
{{ format_date($seminar->onsite_date, -2) }}まで
@elseif ($seminar->seminar_type == 'online')
{{ format_date($seminar->online_date, -1) }}まで
@elseif ($seminar->seminar_type == 'webinar')
視聴期間中にご視聴ください
@endif

※定員に達し次第、受付を終了させていただく場合がございます

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ ご参加の流れ
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

@if ($seminar->seminar_type == 'onsite')
1. 上記URLよりお申し込み
2. お申し込み確認メールの受信
3. 当日、会場へお越しください
   （受付は開始15分前から行います）
@elseif ($seminar->seminar_type == 'online')
1. 上記URLよりお申し込み
2. お申し込み確認メールの受信
3. 参加用URLをお送りします
4. 当日、お送りしたURLからご参加ください
@elseif ($seminar->seminar_type == 'webinar')
1. 上記URLよりお申し込み
2. お申し込み確認メールの受信
3. 視聴用URLをお送りします
4. 期間中、いつでもご視聴いただけます
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お問い合わせについて
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
セミナーに関するご質問やお問い合わせがございましたら、
お気軽に下記お問い合わせ窓口までご連絡ください。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 最後に
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
お忙しい中恐縮ですが、きっと皆様のお役に立てる内容だと
確信しております。

ぜひお気軽にご参加いただけますと幸いです。
皆様のご参加を心よりお待ちしております。

@include('emails.partials.footer')