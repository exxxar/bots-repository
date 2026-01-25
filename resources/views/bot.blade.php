<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <meta name="token" content="{{csrf_token()}}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <!-- Fonts -->

    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">

    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <!-- Scripts -->
    @routes
    @vite(['resources/js/ClientTg/app.js',
    'resources/css/ClientTg/app.css',
    "resources/js/ClientTg/Pages/{$page['component']}.vue"])
    @inertiaHead

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" id="theme" href="https://your-cashman.com/theme6.bootstrap.min.css">
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
<script>

    window.onload = function () {
        let bot = window.currentBot || null

        window.addEventListener('online', () => {
            /*  window.Telegram.WebApp.showAlert("Вы снова онлайн!")*/
            console.log("вы снова онлайн")
        });
        window.addEventListener('offline', () => {
            console.log("вы сейчас офлайн")
            window.Telegram.WebApp.showAlert("Вы сейчас офлайн!")
        });

        let theme = localStorage.getItem("cashman_global_client_theme_" + ((bot || {bot_domain: null}).bot_domain || 'any_bot')) || null

        if (theme) {
            let changeTheme = document.querySelector("#theme")
            changeTheme.href = theme
        }


        const STORAGE_KEY = "sentErrors";

        // Получаем список уже отправленных ошибок из localStorage
        function getSentErrors() {
            try {
                return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
            } catch {
                return [];
            }
        }

        // Сохраняем новый идентификатор ошибки
        function saveErrorId(id) {
            const errors = getSentErrors();
            errors.push(id);
            localStorage.setItem(STORAGE_KEY, JSON.stringify(errors));
        }

        // Генерация уникального идентификатора ошибки
        function generateErrorId(message, source, lineno, colno) {
            return `${message}|${source}|${lineno}|${colno}`;
        }

        // Отправка ошибки на сервер
        function sendError(errorData) {
            fetch("/log-error", {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify(errorData)
            });
        }

        // Глобальный обработчик JS ошибок
        window.onerror = (message, source, lineno, colno, error) => {

            const id = generateErrorId(message, source, lineno, colno);
            const sentErrors = getSentErrors();

            if (!sentErrors.includes(id)) {
                sendError({message, source, lineno, colno, stack: error?.stack});
                saveErrorId(id);
            }

            return false; // не блокируем стандартное поведение
        };

        // Глобальный обработчик необработанных промисов
        window.addEventListener("unhandledrejection", (event) => {
            const id = `promise|${event.reason}`;
            const sentErrors = getSentErrors();

            if (!sentErrors.includes(id)) {
                sendError({type: "unhandledrejection", reason: event.reason});
                saveErrorId(id);
            }
        });

        // Ошибки загрузки ресурсов
        window.addEventListener("error", (event) => {
            if (event.target && event.target.src) {
                const id = `resource|${event.target.src}`;
                const sentErrors = getSentErrors();

                if (!sentErrors.includes(id)) {
                    sendError({type: "resource", src: event.target.src});
                    saveErrorId(id);
                }
            }
        }, true);
    };
</script>

</html>
