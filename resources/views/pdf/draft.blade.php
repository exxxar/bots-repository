<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Чек </title>
</head>
<style>
    th:nth-child(1),
    td:nth-child(1) {
        width: 50px;
    }

    th:nth-child(2),
    td:nth-child(2) {
        width: 250px;
    }

    th:nth-child(3),
    td:nth-child(3) {
        width: 250px;
    }

    th:nth-child(4),
    td:nth-child(4) {
        width: 100px;
    }

    th:nth-child(5),
    td:nth-child(5) {
        width: 100px;
    }

</style>
<body>

<h1>Чек {{$title}}</h1>
<h6>Уникальный идентификатор чека #{{$payload}}</h6>
<hr>
<ul>
    <li>Дата оплаты <strong>{{$current_date}}</strong></li>
    <li>Валюта <strong>{{$currency}}</strong></li>
    <li>Сумма в чеке <strong>{{$total_amount}} руб.</strong></li>
    <li>Идентификатор оплаты провайдера <strong>{{$provider_payment_charge_id}}</strong></li>
    <li>Идентификатор оплаты телеграм <strong>{{$telegram_payment_charge_id}}</strong></li>
</ul>


@if(!empty($order_info??[]))
    <hr>
    <h3>Информация о заказчике:</h3>
    <ul>
        <li>Ф.И.О. <strong>{{$order_info->name ?? '-'}}</strong></li>
        <li>Почта <strong>{{$order_info->email ?? '-'}}</strong></li>
        <li>Номер телефона <strong>{{$order_info->phone_number ?? '-'}}</strong></li>
    </ul>

@endif

@if(!empty($products_info??[]))
    <hr>
    <h3>Ваш заказ состоит из следующих позиций:</h3>
    <table>

        <tr>
            <td><strong>№</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Цена, руб</strong></td>

        </tr>

        @foreach($products_info->prices as $index=>$product)
            <tr>
                <td><strong>{{$index+1}}</strong></td>
                <td><strong>{{$product->label ?? 'не указан'}}</strong></td>
                <td><strong>{{($product->amount ?? 0) / 100}}</strong></td>
            </tr>
        @endforeach

    </table>

    <p><em>{{$products_info->payload??'-'}}</em></p>
@endif

</body>
</html>
