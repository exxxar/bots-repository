<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

abstract class BotCore
{
    protected $domain;

    protected $controller = null;

    protected $bot;

    protected $chatId;

    protected $inline = null;

    protected $routes = [];

    protected $slugs = [];

    protected $next = [];

    protected abstract function createUser($data);

    protected abstract function setWebhooks();

    protected abstract function setApiToken($domain);

    protected abstract function checkIsWorking();

    protected abstract function getSelf();

    public function getCurrentChatId()
    {
        return $this->chatId;
    }

    public function tryCall($item, $message, ...$arguments)
    {

        $find = false;
        try {
            if (is_callable($item["function"])) {
                app()->call($item["function"], [$message, ... $arguments]);
            } else {
                app()->call((!is_null($item["controller"]) ?
                    $item["controller"] . "@" . $item["function"] :
                    $item["function"]),
                    [$message, ... $arguments]);
            }


            $find = true;
        } catch (\Exception $e) {
            Log::error($e->getMessage() . " " . $e->getLine());
        }

        return $find;
    }

    public function inlineHandler($data)
    {
        if (is_null($this->inline))
            return;


        $id = $data["inline_query"]["id"] ?? null;
        $query = $data["inline_query"]["query"] ?? null;

        $this->chatId = $data["inline_query"]["from"]["id"] ?? null;


        $this->tryCall($this->inline, $query, $id);
    }

    public function handler($domain)
    {
        $this->setApiToken($domain);

        $update = $this->bot->getWebhookUpdate();

        include_once base_path('routes/bot.php');

        $item = json_decode($update);


        if (isset($update["channel_post"])) {

            $text = $item->channel_post->text ?? null;

            if (mb_strtolower($text) === "мой id")
                \App\Facades\BotMethods::bot()
                    ->whereDomain($this->getSelf()->bot_domain)
                    ->sendMessage($item->channel_post->sender_chat->id,
                        "Ваш id=>" . $item->channel_post->sender_chat->id
                    );
            return;
        }


        if (isset($update["inline_query"])) {
            $this->createUser($item->inline_query->from);

            $this->inlineHandler($update);
            return;
        }

        //формируем сообщение из возможных вариантов входных данных
        $message = $item->message ??
            $item->edited_message ??
            $item->callback_query->message ??
            null;

        //если сообщения нет, то завершаем работу
        if (is_null($message))
            return;


        //разделяем логику получения данных об отправителе,
        // так как данные приходят в разных частях JSON-объекта,
        // то создадим условие, по которому будем различать откуда получать эти данные

        if (isset($update["callback_query"]))
            $this->createUser($item->callback_query->from);
        else
            $this->createUser($message->from);

        $query = $item->message->text ??
            $item->callback_query->data ?? '';

        $this->chatId = $message->chat->id;

        if (!$this->checkIsWorking()) {

            $message = $this->getSelf()->maintenance_message ?? 'Техническое обслуживание';

            $this
                ->replyPhoto("\xF0\x9F\x9A\xA8В данный момент сервис временно недосутепн! Обратитесь в тех. поддержку:\xF0\x9F\x9A\xA8\n\n<em><b>$message</b></em>",
                    InputFile::create(public_path() . "/images/maintenance.png"),
                    [
                        [
                            ["text" => "\xF0\x9F\x9A\xA7Написать в тех. поддержку", "url" => "https://t.me/exxxar"]
                        ]
                    ]
                );

            return;

        }


        $find = false;

        if (isset($update["message"]["location"])) {
            foreach ($this->routes as $item) {

                if (is_null($item["path"]))
                    continue;

                if ($item["path"] === "location")
                    try {
                        $item["function"]($message, (object)[
                            "lat" => $update["message"]["location"]["latitude"],
                            "lon" => $update["message"]["location"]["longitude"]
                        ]);
                    } catch (\Exception $e) {
                        Log::error($e->getMessage() . " " . $e->getLine());
                    }
            }


            return;
        }

        $matches = [];
        $arguments = [];


        foreach ($this->slugs as $item) {
            if (is_null($item["path"]) || $item["is_service"])
                continue;

            $slug = $item["path"];

            $template = BotMenuSlug::query()
                ->where("bot_id", $this->getSelf()->id)
                ->where("slug", $slug)
                ->first();

            if (is_null($template))
                continue;

            $command = $template->command;

            if (!str_starts_with($command, "/"))
                $command = "/" . $command;


            if (preg_match($command . "$/i", $query, $matches)) {
                foreach ($matches as $match)
                    $arguments[] = $match;

                $find = $this->tryCall($item, $message, $arguments);
                break;
            }

        }

        if (!$find)
            foreach ($this->routes as $item) {

                if (is_null($item["path"]) || $item["is_service"])
                    continue;

                $reg = $item["path"];

                if (!str_starts_with($reg, "/"))
                    $reg = "/" . $reg;

                if (preg_match($reg . "$/i", $query, $matches)) {
                    foreach ($matches as $match)
                        $arguments[] = $match;

                    $find = $this->tryCall($item, $message, ...$arguments);
                    break;
                }

            }

        if (!empty($this->next)) {
            foreach ($this->next as $item) {
                $find = $this->tryCall($item, $message);
            }
        }

        if (!$find) {
            $isFallbackFind = false;
            foreach ($this->routes as $item) {

                if (is_null($item["path"]))
                    continue;

                if ($item["path"] === "fallback") {
                    $isFallbackFind = $this->tryCall($item, $message);
                }
            }

            if (!$isFallbackFind)
                $this->reply("Ошибка обработки данных!");
        }


    }

    public function next($name)
    {
        foreach ($this->routes as $route) {
            if (isset($route["name"]))
                if ($route["name"] == $name)
                    $this->next[] = [
                        "name" => $name,
                        "controller" => $this->controller ?? null,
                        "function" => $route["function"],
                        //  "arguments"=>$arguments??[]
                    ];
        }

        return $this;
    }

    public function controller($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    public function route($path, $function, $name = null)
    {
        $this->routes[] = [
            "path" => $path,
            "is_service" => false,
            "controller" => $this->controller ?? null,
            "function" => $function,
            "name" => $name
        ];

        return $this;
    }

    public function slug($path, $function, $name = null)
    {
        $this->slugs[] = [
            "path" => $path,
            "is_service" => false,
            "controller" => $this->controller ?? null,
            "function" => $function,
            "name" => $name
        ];

        return $this;
    }

    public function location($function)
    {
        $this->routes[] = [
            "path" => "location",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }

    public function fallback($function)
    {
        $this->routes[] = [
            "controller" => $this->controller ?? null,
            "path" => "fallback",
            "is_service" => true,
            "function" => $function
        ];

        return $this;
    }


    public function inline($function)
    {
        $this->inline = [
            "controller" => $this->controller ?? null,
            "function" => $function
        ];

        return $this;
    }

}
