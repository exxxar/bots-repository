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

@if ($history)
    <table>
        <tr>
            <td style="width: 100px;">Бот</td>
            <td style="width: 100px;">Имя</td>
            <td style="width: 100px;">Имя из ТГ</td>
            <td style="width: 100px;">Телефон</td>
            <td style="width: 50px;">VIP</td>
            <td style="width: 50px;">Админ</td>
            <td style="width: 200px;">Описание операции</td>
            <td style="width: 100px;">Тип операции</td>
            <td style="width: 100px;">Денег в чеке,руб</td>
            <td style="width: 100px;">Величина,руб</td>
            <td style="width: 100px;">Уровень</td>
            <td style="width: 100px;">Сотрудник</td>

        </tr>


        @foreach($history as $cashback)
            <tr>
                <td style="width: 100px;">{{$cashback->bot->bot_domain?? 'Не указан'}}</td>
                <td style="width: 100px;">{{$cashback->user->botUser->name?? 'Не указан'}}</td>
                <td style="width: 100px;">{{$cashback->user->botUser->fio_from_telegram?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$cashback->user->botUser->phone?? 'Не указан'}}</td>
                <td style="width: 50px;"> {{$cashback->user->botUser->is_vip ? "да":"нет"}}</td>
                <td style="width: 50px;"> {{$cashback->user->botUser->is_admin ? "да":"нет"}}</td>

                <td style="width: 200px;"> {{$cashback->description?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$cashback->operation_type == 1? "Начисление":"Списание"}}</td>
                <td style="width: 100px;"> {{$cashback->money_in_check?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$cashback->amount?? 'Не указан'}}</td>
                <td style="width: 100px;"> {{$cashback->level?? 'Не указан'}}</td>
                <td style="width: 100px;">{{$cashback->employee->fio_from_telegram?? 'Не указан'}}
                    ({{$cashback->employee->name?? 'Не указан'}})
                </td>


            </tr>
        @endforeach

    </table>
@endif

</body>
</html>
