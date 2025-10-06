<x-mail::message>
    # セミナー申込通知

    申込者: {{ $customer->name }}
    メール: {{ $customer->email }}

    セミナー: {{ $seminar->title }}

    その他申込内容:
    @foreach ($seminarCustomer->toArray() as $key => $value)
        - {{ $key }}: {{ $value }}
    @endforeach

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
