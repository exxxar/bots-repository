
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <meta name="token" content="{{csrf_token()}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="icon" type="image/x-icon" href="/icon.png" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link href="https://fonts.googleapis.com/css2?family=Newsreader:ital,wght@0,600;1,600&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,300;0,500;0,600;0,700;1,300;1,500;1,600;1,700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,400;1,400&amp;display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    @vite(['resources/js/Landing/app.js', "resources/js/Landing/Pages/{$page['component']}.vue"])
    @inertiaHead
    <link rel="stylesheet" id="theme" href="https://your-cashman.com/theme6.bootstrap.min.css">
    <link href="/landingNew/storage/site/libs/swiper/swiper-bundle.min.css" rel="stylesheet" />
    <link href="/landingNew/storage/site/libs/@fancyapps/ui/fancybox.css" rel="stylesheet" />
    <link href="/landingNew/storage/site/libs/intl-tel-input/build/css/intlTelInput.min.css" rel="stylesheet" />
    <link href="/landingNew/storage/site/css/main.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->

</head>
<body class="main-page" data-bg="main.jpg">

@inertia

<!-- Bootstrap core JS-->
<script src="/landingNew/storage/site/libs/jquery/jquery.min.js"></script>
<script src="/landingNew/storage/site/libs/@fancyapps/ui/fancybox.umd.js"></script>
<script src="/landingNew/storage/site/libs/swiper/swiper-bundle.min.js"></script>
<script src="/landingNew/storage/site/libs/inputmask/inputmask.min.js"></script>
<script src="/landingNew/storage/site/libs/lottie-web/build/player/lottie.min.js"></script>
<script src="/landingNew/storage/site/libs/intl-tel-input/build/js/intlTelInput.min.js"></script>
<script src="/landingNew/storage/site/libs/hc-sticky/hc-sticky.js"></script>
<script src="/landingNew/storage/site/js/stories.js"></script>
<script src="/landingNew/storage/site/js/common.js"></script>
</body>
</html>


