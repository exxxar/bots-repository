<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="theme-color" content="#000000">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    <link rel="manifest" href="{{"/mobile/".\Illuminate\Support\Facades\Session::get("domain")."/manifest.json"}}">
    <meta name="token" content="{{csrf_token()}}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Laravel PWA">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- Fonts -->

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @routes
    @vite(['resources/js/Mobile/app.js', "resources/js/Mobile/Pages/{$page['component']}.vue"])
    @inertiaHead

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="/mobile/base.css">
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

    p,
    .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
        font-family: "Roboto", sans-serif;
        font-weight: 300;
    }
</style>

<body class="font-sans antialiased">

@inertia

<script>
    let domain = "{{\Illuminate\Support\Facades\Session::get("domain")}}";

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register(`/mobile/${domain}/sw.js`)
                .then((registration) => {
                    console.log('ServiceWorker registered: ', registration);
                })
                .catch((error) => {
                    console.log('ServiceWorker registration failed: ', error);
                });
        });
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<!-- Splide -->
<script src="/mobile/splide.min.js"></script>
<!-- ProgressBar js -->
<script src="/mobile/progressbar.min.js"></script>
<!-- Base Js File -->
<script src="/mobile/base.js"></script>

<script>
    // Trigger welcome notification after 5 seconds
    setTimeout(() => {
        notification('notification-welcome', 5000);
    }, 2000);


</script>

</body>
<script>
    window.onload = function () {



        window.addEventListener('online', () => {
            /*  window.Telegram.WebApp.showAlert("Вы снова онлайн!")*/
            console.log("вы снова онлайн")
        });
        window.addEventListener('offline', () => {
            console.log("вы сейчас офлайн")
            window.Telegram.WebApp.showAlert("Вы сейчас офлайн!")
        });

    /*    let theme = localStorage.getItem("cashman_global_client_theme_" + ((bot || {bot_domain: null}).bot_domain || 'any_bot')) || null

        if (theme) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = theme
        }
*/

    };
</script>

</html>
