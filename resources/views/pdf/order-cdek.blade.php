<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Чек</title>
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
<h1>Чек</h1>
<h6>Уникальный идентификатор заказа в системе #{{$orderId}}(<strong style='color:darkred'>{{$uniqNumber}}</strong>)</h6>
<h6>Уникальный идентификатор СДЭК #{{$cdekOrderId}}</h6>
<h3>Сервис "{{$title}}"</h3>
<hr>
<ul>
    <li>Имя заказчика <strong>{{$name}}</strong></li>
    <li>Телефон заказчика <strong>{{$phone}}</strong></li>
    <li>Офис отправления <strong>{{$addressFrom}}</strong></li>
    <li>Офис получения <strong>{{$addressTo}}</strong></li>
    <li>Тип оплаты <strong>{{$cashType}}</strong></li>

    @if(!is_null($promocode ?? null))
        <li>Промокод <strong>{{$promocode}}</strong></li>
    @endif
    <li>Сумма заказа <strong>{{$totalPrice }} руб.</strong></li>
    <li>Скидка за CashBack <strong>{{$discount ?? 0 }} руб.</strong></li>
    <li>Итого <strong>{{$totalPrice - ($discount ?? 0) }} руб.</strong></li>

    <li>Количество позиций в заказе <strong>{{$totalCount}} ед.</strong></li>
    <li>Дата и время осуществления заказа <strong>{{$currentDate}}!</strong></li>
    <li>Примерное время доставки от <strong>{{$deliveryMin}}</strong>
        до <strong>{{$deliveryMax}}</strong>
    </li>

</ul>

@if(!empty($products))
    <hr>
    <h3>Ваш заказ состоит из следующих позиций:</h3>
    <table>

        <tr>
            <td><strong>№</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Габариты, см</strong></td>
            <td><strong>Вес, кг</strong></td>
            <td><strong>Цена, руб</strong></td>
            <td><strong>Количество, шт</strong></td>
        </tr>

        @foreach($products as $index=>$product)
            <tr>
                <td><strong>{{$index+1}}</strong></td>
                <td><strong>{{$product->title ?? 'не указан'}}</strong></td>
                <td><strong>
                        {{$product->width ?? 0}}x{{$product->height ?? 0}}x{{$product->length ?? 0}}
                    </strong></td>
                <td><strong>{{$product->weight ?? '0'}}</strong></td>
                <td><strong>{{$product->price ?? 'не указан'}}</strong></td>
                <td><strong>{{$product->count ?? 'не указан'}}</strong></td>
            </tr>
        @endforeach

    </table>
@endif
<hr>
<h3>Как оплатить</h3>
<p>{!! $paymentInfo !!}</p>

<h4>Команда <span style='color:red'>{{$title}}</span> благодарит Вас за использование нашего сервиса! Мы стараемся быть
    лучше для Вас!</h4>
</body>
</html>
