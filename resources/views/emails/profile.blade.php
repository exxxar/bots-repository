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

<h2>Данные о пользователе</h2>
<table>
    <tr>
        <td style="width: 250px;">Идентификатор в телеграм</td>
        <td style="width: 250px;">{{$telegram_chat_id}}</td>
    </tr>
    <tr>
        <td style="width: 250px;">Имя в телеграм</td>
        <td style="width: 250px;">{{$fio_from_telegram}}</td>
    </tr>
    <tr>
        <td style="width: 250px;">Номер телефона</td>
        <td style="width: 250px;">{{$phone ?? 'не указан'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Домен пользователя</td>
        <td style="width: 250px;">{{$username ?? 'не указан'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Почта</td>
        <td style="width: 250px;">{{$email ?? 'не указана'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Дата рождения</td>
        <td style="width: 250px;">{{$birthday ?? 'не указана'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Возраст</td>
        <td style="width: 250px;">{{$age ?? 'не указан'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Пол</td>
        <td style="width: 250px;">{{$sex?? 'не указан'}}</td>
    </tr>

    
    <tr>
        <td style="width: 250px;">Страна</td>
        <td style="width: 250px;">{{$country ?? 'не указана'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Город</td>
        <td style="width: 250px;">{{$city ?? 'не указан'}}</td>
    </tr>

    <tr>
        <td style="width: 250px;">Адрес проживания</td>
        <td style="width: 250px;">{{$address?? 'не указан'}}</td>
    </tr>


</table>


</body>
</html>
