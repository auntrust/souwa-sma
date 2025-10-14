<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>セミナー申込受付完了のお知らせ</title>
    <style>
        body {
            font-family: 'Meiryo', 'MS PGothic', Arial, sans-serif;
            background: #f9f9f9;
            color: #333;
        }

        .container {
            background: #fff;
            padding: 24px;
            margin: 0 auto;
            max-width: 600px;
            border-radius: 8px;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 16px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        th,
        td {
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #ddd;
        }

        th {
            background: #f0f0f0;
            width: 10rem;
            text-align: center;
        }

        .section-title {
            font-weight: bold;
            margin-top: 24px;
            margin-bottom: 8px;
        }

        a {
            color: #0073e6;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>セミナー申込受付完了のお知らせ</h1>
        <p>{{ $request->name }} 様<br>
            この度は「<b>{{ $seminar->name }}</b>」にお申込みいただき、<br>
            誠にありがとうございます。<br>
            お申込み内容を下記の通り受付いたしました。</p>

        <div class="section-title">【お申込みいただいたセミナー】</div>
        <table>
            <tr>
                <th>セミナー名</th>
                <td>{{ $seminar->name }}</td>
            </tr>
            @if ($seminar->seminar_type == 'onsite')
                <tr>
                    <th>開催日</th>
                    <td>{{ format_date($seminar->onsite_date) }}</td>
                </tr>
                <tr>
                    <th>講義時間</th>
                    <td>
                        {{ format_time($seminar->onsite_start_time) }}〜{{ format_time($seminar->onsite_end_time) }}
                        （{{ get_duration_time($seminar->onsite_start_time, $seminar->onsite_end_time) }}分）
                    </td>
                </tr>
                <tr>
                    <th>受講料</th>
                    <td>
                        {{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円' : '無料' }}
                    </td>
                </tr>
                <tr>
                    <th>会場名</th>
                    <td>{{ $seminar->onsite_name }}</td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    <td>{{ format_postal_code($seminar->onsite_zip) }}</td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td>{{ $seminar->onsite_address }}<br/>{{ $seminar->onsite_building }}</td>
                </tr>
                <tr>
                    <th>地図URL</th>
                    <td><a href="{{ $seminar->onsite_map_url }}" target="_blank">{{ $seminar->onsite_map_url }}</a></td>
                </tr>
            @elseif ($seminar->seminar_type == 'online')
                <tr>
                    <th>開催日</th>
                    <td>{{ format_date($seminar->online_date) }}</td>
                </tr>
                <tr>
                    <th>講義時間</th>
                    <td>
                        {{ format_time($seminar->online_start_time) }}〜{{ format_time($seminar->online_end_time) }}
                        （{{ get_duration_time($seminar->online_start_time, $seminar->online_end_time) }}分）
                    </td>
                </tr>
                <tr>
                    <th>受講料</th>
                    <td>
                        {{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円' : '無料' }}
                    </td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td><a href="{{ $seminar->online_url }}" target="_blank">{{ $seminar->online_url }}</a></td>
                </tr>
                <tr>
                    <th>ミーティングID</th>
                    <td>{{ $seminar->online_id }}</td>
                </tr>
                <tr>
                    <th>パスコード</th>
                    <td>{{ $seminar->online_pwd }}</td>
                </tr>
            @elseif ($seminar->seminar_type == 'webinar')
                <tr>
                    <th>受講料</th>
                    <td>
                        {{ $seminar->is_paid == '1' ? number_format($seminar->paid_fee) . '円' : '無料' }}
                    </td>
                </tr>
                <tr>
                    <th>視聴URL</th>
                    <td><a href="{{ $seminar->webinar_url }}" target="_blank">{{ $seminar->webinar_url }}</a></td>
                </tr>
                <tr>
                    <th>視聴可能期間</th>
                    <td>{{ format_datetime($seminar->webinar_start_at) }}〜{{ format_datetime($seminar->webinar_end_at) }}
                    </td>
                </tr>
            @endif
        </table>
        @if ($seminar->seminar_type == 'online')
            <p>※開催時刻になりましたら上記のURLよりご参加ください。</p>
        @endif
        @if ($seminar->seminar_type == 'webinar')
            <p>
                ※開催時刻になりましたら上記のURLよりご参加ください。<br />
                ※視聴は1回のみとなります。</p>
        @endif

        <div class="section-title">【お客様情報】</div>
        <table>
            @if ($request->name)
                <tr>
                    <th>氏名</th>
                    <td>{{ $request->name }}</td>
                </tr>
            @endif
            @if ($request->furigana)
                <tr>
                    <th>ふりがな</th>
                    <td>{{ $request->furigana }}</td>
                </tr>
            @endif
            @if ($request->tel)
                <tr>
                    <th>電話番号</th>
                    <td>{{ $request->tel }}</td>
                </tr>
            @endif
            @if ($request->email)
                <tr>
                    <th>メールアドレス</th>
                    <td>{{ $request->email }}</td>
                </tr>
            @endif
            @if ($request->todofuken)
                <tr>
                    <th>都道府県</th>
                    <td>{{ $request->todofuken }}</td>
                </tr>
            @endif
        </table>

        @if ($request->co_name || $request->co_tel || $request->co_busho || $request->co_post)
        <div class="section-title">【会社情報】</div>
        <table>
            @if ($request->co_name)
                <tr>
                    <th>会社名</th>
                    <td>{{ $request->co_name }}</td>
                </tr>
            @endif
            @if ($request->co_tel)
                <tr>
                    <th>電話番号</th>
                    <td>{{ $request->co_tel }}</td>
                </tr>
            @endif
            @if ($request->co_busho)
                <tr>
                    <th>部署</th>
                    <td>{{ $request->co_busho }}</td>
                </tr>
            @endif
            @if ($request->co_post)
                <tr>
                    <th>役職</th>
                    <td>{{ $request->co_post }}</td>
                </tr>
            @endif
        </table>
        @endif

        <div class="section-title">【その他】</div>
        <table>
            @if ($request->applicant_count)
                <tr>
                    <th>参加人数</th>
                    <td>{{ $request->applicant_count }} 名</td>
                </tr>
            @endif
            @if ($request->request)
                <tr>
                    <th>ご質問・要望</th>
                    <td>{!! nl2br(e($request->get('request'))) !!}</td>
                </tr>
            @endif
        </table>

        <p>後日、詳細のご案内をメールにてお送りいたします。<br>
            ご不明点等ございましたら、お気軽にお問い合わせください。<br>
            今後ともよろしくお願いいたします。</p>
    </div>
</body>

</html>
