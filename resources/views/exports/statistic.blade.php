<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Статистика бота</title>
</head>
<body>

@if ($statistic)
    <table>
        <tr>
            <td style="width: 300px;">Параметр</td>
            <td style="width: 100px;">Значение</td>
        </tr>

        <tr>
            <td style="width: 300px;">Пользователей в БД всего</td>
            <td style="width: 100px;">{{$statistic['users_in_bd'] ?? 0 }}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Пользователей в БД за сегодня</td>
            <td style="width: 100px;">{{$statistic['users_in_bd_today'] ?? 0}}</td>
        </tr>


        <tr>
            <td style="width: 300px;">VIP пользователей в БД всего</td>
            <td style="width: 100px;">{{$statistic['vip_in_bd'] ?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">VIP пользователей в БД за сегодня</td>
            <td style="width: 100px;">{{$statistic['vip_in_bd_today'] ?? 0 }}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Администраторов в БД всего</td>
            <td style="width: 100px;">{{$statistic['admin_in_bd'] ?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Администраторов за работой</td>
            <td style="width: 100px;">{{$statistic['work_admin_in_bd']?? 0 }}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно начислений CashBack в рублях всего</td>
            <td style="width: 100px;">{{$statistic['summary_cashback']?? 0}}</td>
        </tr>
        <tr>
            <td style="width: 300px;">Суммарно списаний CashBack в рублях всего</td>
            <td style="width: 100px;">{{$statistic['cashback_summary_down']?? 0}}</td>
        </tr>


        <tr>
            <td style="width: 300px;">Суммарно CashBack в рублях за сегодня</td>
            <td style="width: 100px;">{{$statistic['cashback_day_up']?? 0}}</td>
        </tr>
        <tr>
            <td style="width: 300px;">Суммарно списаний CashBack в рублях за сегодня</td>
            <td style="width: 100px;">{{$statistic['cashback_day_down']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 1 уровня в рублях всего</td>
            <td style="width: 100px;">{{$statistic['cashback_summary_up_level_1']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 2 уровня в рублях всего</td>
            <td style="width: 100px;">{{$statistic['cashback_summary_up_level_2']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 3 уровня в рублях всего</td>
            <td style="width: 100px;">{{$statistic['cashback_summary_up_level_3']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 1 уровня в рублях за сегодня</td>
            <td style="width: 100px;">{{$statistic['cashback_day_up_level_1']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 2 уровня в рублях за сегодня</td>
            <td style="width: 100px;">{{$statistic['cashback_day_up_level_2']?? 0}}</td>
        </tr>

        <tr>
            <td style="width: 300px;">Суммарно CashBack 3 уровня в рублях за сегодня</td>
            <td style="width: 100px;">{{$statistic['cashback_day_up_level_3']?? 0}}</td>
        </tr>
    </table>
@endif

</body>
</html>
