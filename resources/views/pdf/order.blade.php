<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Счет на оплату</title>
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
<h1>Счет на оплату</h1>
<h6>Уникальный идентификатор заказа #{{$orderId}}(<strong style='color:darkred'>{{$uniqNumber}}</strong>)</h6>
<h3>Сервис "{{$title}}"</h3>
<hr>
<ul>
    <li>Имя заказчика <strong>{{$name}}</strong></li>
    <li>Телефон заказчика <strong>{{$phone}}</strong></li>
    <li>Адрес заказчика <strong>{{$address}}</strong></li>
    <li>Номер подъезда <strong>{{$entranceNumber}}</strong></li>
    <li>Номер этажа <strong>{{$floorNumber}}</strong></li>
    <li>Тип оплаты <strong>{{$cashType}}</strong></li>
    <li>Сдача с <strong>{{$money}}</strong></li>
    <li>Ограничения по здоровью <strong>{!! $disabilitiesText !!}</strong></li>

    <li>Дополнительная информация от заказчика <strong>{!! $message !!}</strong>
    </li>
    <li>Сумма заказа <strong>{{$totalPrice }} руб.</strong></li>
    <li>Скидка за CashBack <strong>{{$discount ?? 0 }} руб.</strong></li>
    <li>Итого к оплате <strong>{{$totalPrice - ($discount ?? 0) }} руб.</strong></li>
    @if($distance>0)
        <li>Дистанция доставки <strong>{{$distance }} м</strong></li>
    @endif
    <li>Количество позиций в заказе <strong>{{$totalCount}} ед.</strong></li>
    <li>Дата и время осуществления заказа <strong>{{$currentDate}}!</strong></li>
    <li>Другая информация от вас <strong>{{$info}}!</strong></li>
</ul>

@if(!empty($products))
    <hr>
    <h3>Ваш заказ состоит из следующих позиций:</h3>
    <table>

        <tr>
            <td><strong>№</strong></td>
            <td><strong>Название</strong></td>
            <td><strong>Цена, руб</strong></td>
            <td><strong>Количество, шт</strong></td>
        </tr>

        @foreach($products as $index=>$product)
            <tr>
                <td><strong>{{$index+1}}</strong></td>
                <td><strong>{{$product->title ?? 'не указан'}}</strong></td>
                <td><strong>{{$product->price ?? 'не указан'}}</strong></td>
                <td><strong>{{$product->count ?? 'не указан'}}</strong></td>
            </tr>
        @endforeach

    </table>
@endif
<hr>
<h3>Как оплатить</h3>
<p>{!! $paymentInfo !!}</p>
<!--<h3>Ваш промокод для участия в акциях:</h3>
<p>{{$code}} - всего доступно <strong>{{$promoCount}}</strong> активаций </p>-->
<h4>Команда <span style='color:red'>{{$title}}</span> благодарит Вас за использование нашего сервиса! Мы стараемся быть
    лучше для Вас!</h4>
</body>
</html>
