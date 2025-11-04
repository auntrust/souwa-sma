{{ $request->name }} 様

この度は「{{ $seminar->name }}」に
お申込みいただき、誠にありがとうございます。

お申込み内容を下記の通り受付いたしました。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ セミナー詳細
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

【セミナー名】
{{ $seminar->name }}

@if ($seminar->seminar_type == 'onsite')
【開催日時】
{{ format_date($seminar->onsite_date) }}（{{ \Carbon\Carbon::parse($seminar->onsite_date)->isoFormat('dddd') }}）
{{ format_time($seminar->onsite_start_time) }}〜{{ format_time($seminar->onsite_end_time) }}
（所要時間：{{ get_duration_time($seminar->onsite_start_time, $seminar->onsite_end_time) }}分）

【会場】
{{ $seminar->onsite_name }}
{{ format_postal_code($seminar->onsite_zip) }}
{{ $seminar->onsite_address }}
@if ($seminar->onsite_building)
{{ $seminar->onsite_building }}
@endif

【地図】{{ $seminar->onsite_map_url }}

🔶 重要：会場参加について
・受付開始：開始15分前から
・遅刻の場合は、途中入場が困難な場合があります
・公共交通機関でのお越しをお勧めします

@elseif ($seminar->seminar_type == 'online')
【開催日時】
{{ format_date($seminar->online_date) }}（{{ \Carbon\Carbon::parse($seminar->online_date)->isoFormat('dddd') }}）
{{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}
（所要時間：{{ get_duration_time($seminar->online_start_time, $seminar->online_end_time) }}分）

【オンライン接続情報】
・参加URL：{{ $seminar->online_url }}
・ミーティングID：{{ $seminar->online_id }}
・パスコード：{{ $seminar->online_pwd }}

🔶 重要：オンライン参加について
・入室開始：開始10分前から
・事前に音声・映像の動作確認をお済ませください
・安定したインターネット環境でご参加ください
・マイクはミュート設定でご参加をお願いします

@elseif ($seminar->seminar_type == 'webinar')
【ウェビナー情報】
・視聴URL：{{ $seminar->webinar_url }}
・視聴期間：{{ format_datetime($seminar->webinar_start_at) }}
　　　　　　〜{{ format_datetime($seminar->webinar_end_at) }}

🔶 重要：ウェビナー視聴について
・視聴は期間中1回のみとなります
・開始日時になりましたら上記URLからアクセスしてください
・録画の配布や再視聴はご遠慮ください

@endif
【受講料】
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円（税込）' : '無料' }}
@if ($request->applicant_count)

【参加予定人数】
{{ $request->applicant_count }}名
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 当日の準備事項
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if ($seminar->seminar_type == 'onsite')
✓ 筆記用具
✓ 名刺（交流がある場合のため）
✓ 身分証明書（受付時に確認させていただく場合があります）

@elseif ($seminar->seminar_type == 'online')
✓ インターネット接続可能なデバイス（PC・タブレット推奨）
✓ ヘッドフォンまたはイヤフォン
✓ 筆記用具
✓ 静かな環境の確保

@else
✓ インターネット接続可能なデバイス（PC・タブレット推奨）
✓ ヘッドフォンまたはイヤフォン
✓ 筆記用具

@endif
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お申込み者様情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if ($request->name)
氏名　　　：{{ $request->name }}
@endif
@if ($request->furigana)
ふりがな　：{{ $request->furigana }}
@endif
@if ($request->email)
メール　　：{{ $request->email }}
@endif
@if ($request->tel)
電話番号　：{{ $request->tel }}
@endif
@if ($request->co_name)
ご所属　　：{{ $request->co_name }}
@endif
@if ($request->co_busho)
部署　　　：{{ $request->co_busho }}
@endif
@if ($request->co_post)
役職　　　：{{ $request->co_post }}
@endif
@if ($request->request)

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ ご質問・ご要望
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
{!! e($request->get('request')) !!}
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 今後のご案内
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if ($seminar->seminar_type == 'onsite')
・開催日前日に、最終確認のメールをお送りします
・当日の緊急連絡先も別途ご案内いたします
@elseif ($seminar->seminar_type == 'online')
・開催日前日に、接続テスト用のURLをお送りします
・当日のトラブル対応についてもご案内いたします
@else
・視聴開始日前日に、最終案内をお送りします
@endif

皆様のご参加を心よりお待ちしております。
ご不明点等ございましたら、お気軽にお問い合わせください。
@if ($seminar->seminar_type == 'onsite' || $seminar->seminar_type == 'online')

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ キャンセル・変更について
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
やむを得ずキャンセルまたは変更をご希望の場合は、
お早めに下記お問い合わせ窓口までご連絡ください。
@endif

@include('emails.partials.footer')