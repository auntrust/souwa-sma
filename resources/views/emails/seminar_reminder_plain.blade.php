{{ $participant->name }} 様

いつもお世話になっております。

いよいよ明日開催の「{{ $seminar->name }}」についてご案内いたします。
最終確認をお願いいたします。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ セミナー詳細（最終確認）
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

【地図】
{{ $seminar->onsite_map_url }}

🔶 明日の会場参加について（重要）
・受付開始：{{ format_time(\Carbon\Carbon::parse($seminar->onsite_start_time)->subMinutes(15)) }}から
・遅刻の場合は、途中入場が困難な場合があります  
・交通状況を考慮し、時間に余裕をもってお越しください
・駐車場の有無については会場に直接お問い合わせください

⚠️ 当日の緊急連絡先
{{ $seminar->organizer_name }}：{{ $seminar->organizer_tel ?? 'お問い合わせ窓口まで' }}

@elseif ($seminar->seminar_type == 'online')
【開催日時】
{{ format_date($seminar->online_date) }}（{{ \Carbon\Carbon::parse($seminar->online_date)->isoFormat('dddd') }}）
{{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}
（所要時間：{{ get_duration_time($seminar->online_start_time, $seminar->online_end_time) }}分）

【オンライン接続情報】
・参加URL：{{ $seminar->online_url }}
・ミーティングID：{{ $seminar->online_id }}
・パスコード：{{ $seminar->online_pwd }}

🔶 明日のオンライン参加について（重要）
・入室開始：{{ format_time(\Carbon\Carbon::parse($seminar->online_start_time)->subMinutes(10)) }}から
・今夜のうちに音声・映像の動作確認をお済ませください
・安定したインターネット環境でご参加ください
・マイクはミュート設定でお願いします

✅ 接続テスト推奨
明日の参加前に、一度上記URLで接続テストを行うことをお勧めします。

⚠️ トラブル時の対応
接続できない場合は、{{ $seminar->organizer_email ?? 'お問い合わせ窓口' }}までご連絡ください。

@elseif ($seminar->seminar_type == 'webinar')
【ウェビナー情報】
・視聴URL：{{ $seminar->webinar_url }}
・視聴期間：{{ format_datetime($seminar->webinar_start_at) }}
　　　　　　〜{{ format_datetime($seminar->webinar_end_at) }}

🔶 明日のウェビナー視聴について（重要）
・配信開始時刻：{{ format_time(\Carbon\Carbon::parse($seminar->webinar_start_at)) }}
・視聴は期間中1回のみとなります
・開始時刻になりましたら上記URLからアクセスしてください
・録画の配布や再視聴はできません

⚠️ 視聴に関する注意事項
・視聴途中でブラウザを閉じると再視聴できません
・安定したインターネット環境での視聴をお勧めします

@endif
【受講料】
{{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円（税込）' : '無料' }}
@if ($participant->applicant_count)

【参加予定人数】
{{ $participant->applicant_count }}名
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ 明日の準備チェックリスト
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
@if ($seminar->seminar_type == 'onsite')
□ 筆記用具の準備完了
□ 名刺の準備（交流がある場合のため）
□ 会場までのルート確認済み
□ 交通手段・所要時間の確認済み
□ 身分証明書の携帯（受付時に必要な場合があります）

@elseif ($seminar->seminar_type == 'online')
□ デバイス（PC・タブレット推奨）の動作確認済み
□ インターネット接続の安定性確認済み
□ ヘッドフォン・イヤフォンの準備完了
□ 筆記用具の準備完了
□ 静かな環境の確保済み
□ カメラ・マイクの設定確認済み

@else
□ デバイス（PC・タブレット推奨）の動作確認済み
□ インターネット接続の安定性確認済み
□ ヘッドフォン・イヤフォンの準備完了
□ 筆記用具の準備完了
□ 集中できる環境の確保済み

@endif
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ ご登録情報（確認用）
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
明日は貴重なお時間をいただき、誠にありがとうございます。
充実したセミナーになるよう努めてまいります。

万が一、急な体調不良等でご参加が困難になった場合は、
お早めにご連絡をお願いいたします。

皆様のご参加を心よりお待ちしております。
@if ($seminar->seminar_type == 'onsite' || $seminar->seminar_type == 'online')

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ キャンセル・変更について
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
やむを得ずキャンセルまたは変更をご希望の場合は、
お早めに下記お問い合わせ窓口までご連絡ください。
@endif

@include('emails.partials.footer')