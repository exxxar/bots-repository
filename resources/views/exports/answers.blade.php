<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Результаты анкетирования</title>
</head>
<body>
@if ($user)
    <h2>Данные о пользователе</h2>
    <table>
        <tr>
            <td style="width: 100px;">Идентификатор в телеграм</td>
            <td style="width: 100px;">{{$user->telegram_chat_id}}</td>
        </tr>
        <tr>
            <td style="width: 100px;">Имя в телеграм</td>
            <td style="width: 100px;">{{$user->fio_from_telegram}}</td>
        </tr>
        <tr>
            <td style="width: 100px;">Номер телефона</td>
            <td style="width: 100px;">{{$user->phone ?? 'не указан'}}</td>
        </tr>
    </table>
@endif
@if ($answers)
    <h2>Результаты анкетирования</h2>
    <table>
        <tr>
            <td style="width: 150px;">Ключ (переменная)</td>
            <td style="width: 100px;">Пользователь ввёл</td>
            <td style="width: 1000px;">Сохраненные данные</td>

        </tr>

        @foreach($answers as $answer)
            @php
                $answer = (object)$answer;
            @endphp
            @if(!empty($answer->value ?? '')||!empty($answer->custom_stored_value ?? ''))
                <tr>
                    <td style="width: 150px;">{{$answer->key?? 'Не указан'}}</td>
                    <td style="width: 100px;">{{$answer->value?? 'Не указан'}}</td>
                    <td style="width: 1000px;">{{$answer->custom_stored_value?? 'Не указан'}}</td>
                </tr>
            @endif
        @endforeach

    </table>
@endif

</body>
</html>
