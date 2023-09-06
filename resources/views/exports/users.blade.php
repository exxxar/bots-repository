<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Пользователи бота</title>
</head>
<body>

@if ($users)
    <table>
        <tr>
            <td style="width: 100px;">Имя</td>
            <td style="width: 100px;">Имя из ТГ</td>
            <td style="width: 100px;">Телефон</td>
            <td style="width: 100px;">Почта</td>
            <td style="width: 100px;">ДР</td>
            <td style="width: 100px;">Возраст</td>
            <td style="width: 100px;">Страна</td>
            <td style="width: 100px;">Город</td>
            <td style="width: 100px;">Адрес</td>
            <td style="width: 50px;">Пол</td>
            <td style="width: 100px;">Бот</td>
            <td style="width: 100px;">Сумма CashBack,руб</td>
            <td style="width: 100px;">Chat ID</td>
            <td style="width: 50px;">VIP</td>
            <td style="width: 50px;">Админ</td>
        </tr>

        @foreach($users as $user)
            <tr>
                <td style="width: 100px;">{{$user->name?? 'Не указан'}}</td>
                <td style="width: 100px;">{{$user->fio_from_telegram?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->phone?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->email?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->birthday?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->age?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->country?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->city?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->address?? 'Не указан'}}</td>
                <td style="width: 50px;"> {{$user->sex ? "Мужской":"Женский"}}</td>
                <td style="width: 100px;"> {{$user->bot->bot_domain ?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$user->cashBack->amount ?? '0'}}</td>
                <td style="width: 100px;"> {{$user->telegram_chat_id ?? 'Не указан'}}</td>
                <td style="width: 50px;"> {{$user->is_vip ? "да":"нет"}}</td>
                <td style="width: 50px;"> {{$user->is_admin ? "да":"нет"}}</td>
            </tr>
        @endforeach

    </table>
@endif

</body>
</html>
