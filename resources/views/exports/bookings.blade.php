<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Выгрузка броней</title>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>ID</th>
        <th style="width:100px;">Столик №</th>
        <th style="width:100px;">Число персон</th>
        <th style="width:100px;">Дата</th>
        <th style="width:100px;">Время</th>
        <th style="width:200px;">Описание</th>
        <th style="width:100px;">Имя</th>
        <th style="width:100px;">Телефон</th>
    </tr>
    </thead>
    <tbody>
    @foreach($bookings as $booking)
        <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->number }}</td>
            <td>{{ $booking->booked_info["persons"] ?? 1 }}</td>
            <td>{{ $booking->booked_date_at }}</td>
            <td>{{ $booking->booked_time_at }}</td>
            <td>{{ $booking->booked_info["description"] ?? '-' }}</td>
            <td>{{ $booking->booked_info["name"] ?? '-' }}</td>
            <td>{{ $booking->booked_info["phone"] ?? '-' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>
