<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover"/>
    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" type="text/css" href="/shop/styles/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/shop/styles/style.css">
    <link
        href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700,800,900|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&amp;display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!--        <link rel="manifest" href="_manifest.json" data-pwa-version="set_in_manifest_and_pwa_js">
            <link rel="apple-touch-icon" sizes="180x180" href="app/icons/icon-192x192.png">-->

    <meta name="token" content="{{csrf_token()}}">


    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="theme-light" data-highlight="orange">
@inertia
<script type="text/javascript" src="/shop/scripts/jquery.js"></script>
<script type="text/javascript" src="/shop/scripts/bootstrap.min.js"></script>
</body>
</html>
