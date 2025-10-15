━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
■ お問い合わせ窓口
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ご不明点やご質問がございましたら、
お気軽に下記までお問い合わせください。

【主催者】{{ $seminar->organizer_name }}
@if ($seminar->organizer_tel)
【電話】{{ $seminar->organizer_tel }}（平日 9:00〜18:00）
@endif
@if ($seminar->organizer_email)
【メール】{{ $seminar->organizer_email }}
@endif

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

※ このメールは送信専用アドレスから配信しています
※ お問い合わせは上記専用窓口をご利用ください

今後ともどうぞよろしくお願いいたします。

────────────────────────────────────
{{ $seminar->organizer_name }} セミナー事務局
@if ($seminar->organizer_tel)
TEL: {{ $seminar->organizer_tel }}  
@endif
@if ($seminar->organizer_email)
Email: {{ $seminar->organizer_email }}
@endif
────────────────────────────────────