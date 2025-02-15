<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <meta name="token" content="{{csrf_token()}}">

    <meta name="bot-user" content="{{Session::get("bot_user")}}">
    <meta name="bot" content="{{Session::get("bot")}}">



    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>

    <script src="https://telegram.org/js/telegram-web-app.js"></script>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/AdminPanel/app.js','resources/css/AdminPanel/app.css',
    "resources/js/AdminPanel/Pages/{$page['component']}.vue"])
    @inertiaHead

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <link rel="stylesheet" id="theme" href="/theme16.bootstrap.min.css">
</head>
<style>
    body {
        font-family: "Roboto", sans-serif;
        font-weight: 100;
        font-style: normal;
    }

    .roboto-thin {
        font-family: "Roboto", sans-serif;
        font-weight: 100;
        font-style: normal;
    }

    .roboto-light {
        font-family: "Roboto", sans-serif;
        font-weight: 300;
        font-style: normal;
    }

    .roboto-regular {
        font-family: "Roboto", sans-serif;
        font-weight: 400;
        font-style: normal;
    }

    .roboto-medium {
        font-family: "Roboto", sans-serif;
        font-weight: 500;
        font-style: normal;
    }

    .roboto-bold {
        font-family: "Roboto", sans-serif;
        font-weight: 700;
        font-style: normal;
    }

    .roboto-black {
        font-family: "Roboto", sans-serif;
        font-weight: 900;
        font-style: normal;
    }

    .roboto-thin-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 100;
        font-style: italic;
    }

    .roboto-light-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 300;
        font-style: italic;
    }

    .roboto-regular-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 400;
        font-style: italic;
    }

    .roboto-medium-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 500;
        font-style: italic;
    }

    .roboto-bold-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 700;
        font-style: italic;
    }

    .roboto-black-italic {
        font-family: "Roboto", sans-serif;
        font-weight: 900;
        font-style: italic;
    }

</style>
<script>

    let theme = localStorage.getItem("cashman_global_admin_theme") || null

    if (theme) {
        let changeTheme = document.querySelector("#theme")
        changeTheme.href = theme
    }


</script>


<body class="font-sans antialiased theme-light" style="overflow-y: auto;">

@inertia


<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>

</body>
</html>
