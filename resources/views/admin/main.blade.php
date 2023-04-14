<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
        const tg = window.Telegram.WebApp;

        const urlParams = new URLSearchParams(tg.initData);
        const user = JSON.parse(urlParams.get('user'));

        document.write(user.id);
        document.write("<br>"+tg.version);

        function scanQR(){
            tg.showScanQrPopup({
                text:'test'
            }, (data)=>{
                document.write("<br>"+data);
                tg.closeScanQrPopup()
                return true
            })
        }
        function openLink(url){
            tg.openLink(url, {
                try_instant_view:true,
            })
        }

    </script>
</head>
<body>
<h1>Hello, world!</h1>
<div class="container">
    <div class="row">
        <div class="col-12">
            <a onclick="scanQR()"  class="btn btn-outline-primary w-100 mb-2">Сканировать QR</a>
            <a onclick="openLink('/admin/cashback-remove')"  class="btn btn-outline-primary w-100 mb-2">Списать CashBack</a>
            <a onclick="openLink('/admin/cashback-add')"  class="btn btn-outline-primary w-100 mb-2">Начислить CashBack</a>
            <a onclick="openLink('/admin/admin-add')"   class="btn btn-outline-primary w-100 mb-2">Добавить администратора</a>
            <a onclick="openLink('/admin/admin-remove')"  class="btn btn-outline-primary w-100 mb-2">Убрать администратора</a>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body>
</html>
