<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>QR-коды</title>

    <style type="text/css">
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px dotted black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h1>QR-коды для столиков</h1>
@php
    $index = 0;
@endphp
<table style="width:100%;">
    @foreach($tables as $table)
        <tr>
            @foreach($table as $row)
                <td>
                    <center>
                        <h6 style="width:100%; text-align:center;">Столик № {{$row->id}}</h6><br>
                        <img
                            style="width:250px;height:250px;"
                            src="{{$row->qr}}" alt="">
                    </center>

                </td>
            @endforeach
        </tr>
    @endforeach
</table>


</body>
</html>

