<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Продукты</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>

@if ($products)
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Артикул</th>
            <th>VK ID продукта</th>
            <th>Frontpad артикул</th>
            <th>Iiko артикул</th>
            <th>Название</th>
            <th>Условия доставки</th>
            <th>Описание</th>
            <th>Изображения</th>
            <th>Тип</th>
            <th>Рейтинг</th>
            <th>Старая цена</th>
            <th>Текущая цена</th>
            <th>Варианты</th>
            <th>Дата остановки</th>
            <th>Не для доставки</th>
            <th>Размеры</th>
            <th>Bot ID</th>
            <th>Дата удаления</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->article }}</td>
                <td>{{ $product->vk_product_id }}</td>
                <td>{{ $product->frontpad_article }}</td>
                <td>{{ $product->iiko_article }}</td>
                <td>{{ $product->title }}</td>
                <td>{{ $product->delivery_terms }}</td>
                <td>{{ $product->description }}</td>
                <td>
                    @foreach($product->images ?? [] as $img)
                        <p>{{$img}}</p>
                    @endforeach
                </td>
                <td>{{ $product->type }}</td>
                <td>{{ $product->rating }}</td>
                <td>{{ $product->old_price }}</td>
                <td>{{ $product->current_price }}</td>
                <td>
                    @foreach($product->variants ?? [] as $var)
                        <p>{{$var}}</p>
                    @endforeach
                </td>
                <td>{{ optional($product->in_stop_list_at)->format('Y-m-d H:i:s') }}</td>
                <td>{{ $product->not_for_delivery ? 'Yes' : 'No' }}</td>
                <td>
                    @foreach($product->dimension ?? [] as $d)
                        <p>{{$d}}</p>
                    @endforeach
                </td>
                <td>{{ $product->bot_id }}</td>
                <td>{{ optional($product->deleted_at)->format('Y-m-d H:i:s') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>
