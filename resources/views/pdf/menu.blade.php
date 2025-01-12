<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Меню ресторана</title>
    <style type="text/css">
        body {
            background-color: #333;
            color: white;
            font-family: Arial, sans-serif;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 36px;
            margin: 10px 0;
        }

        .header p {
            font-size: 16px;
            margin: 5px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin: 20px 0;
        }

        td {
            border: 1px solid #555;
            padding: 20px;
            text-align: center;
            vertical-align: center;
            background-color: #444;
        }

        .product-card {
            /*display: flex;
            flex-direction: column;
            align-items: center;*/
            padding: 10px;
            margin: 10px 0px;
        }

        .product-image {
            width: 250px;
            height: 250px;
            background-color: #666;
            margin-bottom: 10px;
            overflow: hidden;
        }

        .product-name {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
            line-height: 1.5;
        }

        .product-description {
            font-size: 14px;
            margin: 10px 0;
            font-style: italic;
            line-height: 1.5;
        }

        .product-price {
            font-size: 16px;
            font-weight: bold;
            color: #f0ad4e;
            margin: 5px 0;
            line-height: 1.5;
        }

        .product-discount {
            font-size: 14px;
            color: #d9534f;
            margin: 5px 0;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Ресторан "Вкусно и Точка"</h1>
    <p>Телефон: +7 (123) 456-78-90</p>
    <p>Email: info@vkusno.ru</p>
    <p>Часы работы: 10:00 - 22:00</p>
</div>

<table>

    @foreach($tables as $table)
        <tr>
            @foreach($table as $row)
                <td>
                    <div class="product-card">
                        <div class="product-image">
                            <img
                                style="width:250px;"
                                src="{{$row->image}}" alt="">
                        </div>
                        <div class="product-name">{{$row->title}}</div>
                        <div class="product-description">{{$row->description}}</div>
                        <div class="product-price">Цена: {{$row->current_price}} руб.</div>
                        @if($row->old_price!=0)
                            <div class="product-discount">Прошлая цена: {{$row->old_price}} руб.</div>
                        @endif
                    </div>
                </td>
            @endforeach
        </tr>
    @endforeach


</table>

<div class="footer">
    <p>Желаем вам приятного аппетита и хорошего настроения!</p>
</div>
</body>
</html>
