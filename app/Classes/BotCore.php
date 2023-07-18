<?php

namespace App\Classes;

use App\Enums\BotStatusEnum;
use App\Facades\BotManager;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use ReflectionClass;
use Telegram\Bot\FileUpload\InputFile;

abstract class BotCore
{
    use  BotWebInterfaceTrait;

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

    protected abstract function prepareTemplatePage($page);

    protected abstract function botStatusHandler(): BotStatusEnum;

    protected abstract function nextBotDialog($text): void;

    protected abstract function startBotDialog($dialogCommandId): void;

    protected abstract function currentBotUserInDialog(): bool;

    protected abstract function stopBotDialog(): void;

    protected function selfScriptDiagnostic($item): void
    {
        try {

            $tmp = Collection::make($this->slugs)
                ->where("path", $item->slug)
                ->first();

            if (is_null($tmp)) {
                Log::warning("Script $item->slug not found in system");
                return;
            }

            $refl = new ReflectionClass($tmp["controller"]);

            $slugActualKeyCollection = Collection::make($item->config)
                ->pluck("key")
                ->toArray();

            $tmp = $item->config ?? [];

            foreach ($refl->getConstants() as $key => $const) {
                if (str_starts_with($key, "KEY_") && !in_array($const, $slugActualKeyCollection)) {


                    $type = (str_starts_with($const, "need_") || str_starts_with($const, "is_")) ?
                        "boolean" : "text";

                    $tmp[] = (object)[
                        "key" => $const,
                        "value" => "",
                        "type" => $type,
                    ];
                }

            }

            if (count($tmp) > count($item->config ?? [])) {
                $item->config = $tmp;
                $item->save();
            }

        } catch (\Exception $e) {
            Log::error("Diagnostic module fail:" . $e->getMessage());
        }
    }

    public function getCurrentChatId()
    {
        return $this->chatId;
    }

    public function tryCall($item, $message, $config = null, ...$arguments)
    {

        //$config = is_null($config) ? null : json_decode($config);

        $find = false;
        try {
            if (is_callable($item["function"])) {
                app()->call($item["function"], [$message, $config, ... $arguments]);
            } else {
                app()->call((!is_null($item["controller"]) ?
                    $item["controller"] . "@" . $item["function"] :
                    $item["function"]),
                    [$message, $config, ... $arguments]);
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


        $this->tryCall($this->inline, $query, null, $id);
    }

    public function webHandler($domain, $data): \Illuminate\Http\JsonResponse
    {
        $this->isWebMode = true;

        $this->setApiToken($domain);

        $query = $data->message ?? $data->query ?? null;

        $user = (object)$data->user;
        $this->chatId = $user->id;

        include_once base_path('routes/bot.php');

        $this->createUser($user);

        $botStatus = $this->botStatusHandler();

        if ($botStatus != BotStatusEnum::Working)
            return $this->webMessages;

        /*    if ($this->botDialogStartHandler($data, $query))
                return response()->json($this->webMessages);*/

        if ($this->botTemplatePageHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botSlugHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botRouteHandler($data, $query))
            return response()->json($this->webMessages);

        if ($this->botNextHandler($data))
            return response()->json($this->webMessages);

        if ($this->botFallbackHandler($data))
            return response()->json($this->webMessages);

        $this->reply("Ошибка обработки данных!");
        return response()->json($this->webMessages);
    }

    private function botLocationHandler($coords, $message): bool
    {
        if (is_null($coords))
            return false;

        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "location")
                try {
                    $item["function"]($message, (object)[
                        "lat" => $coords->latitude,
                        "lon" => $coords->longitude
                    ]);
                    return true;
                } catch (\Exception $e) {
                    Log::error($e->getMessage() . " " . $e->getLine());
                }
        }

        return false;
    }

    private function botSlugHandler($message, $query): bool
    {
        $matches = [];
        $arguments = [];

        $find = false;
        foreach ($this->slugs as $item) {
            if (is_null($item["path"]) || $item["is_service"])
                continue;
            $slug = $item["path"];
            $templates = BotMenuSlug::query()
                ->where("bot_id", $this->getSelf()->id)
                ->where("slug", $slug)
                ->orderBy("updated_at", "DESC")
                ->get();

            if (count($templates) == 0)
                continue;

            foreach ($templates as $template) {
                $command = $template->command;

                if (!str_starts_with($command, "/"))
                    $command = "/" . $command;
                if (preg_match($command . "$/i", $query, $matches)) {
                    foreach ($matches as $match)
                        $arguments[] = $match;


                    $this->selfScriptDiagnostic($template);

                    $find = $this->tryCall($item, $message, $template->config ?? null, ...$arguments);
                    break;
                }
            }
            if ($find)
                return true;

        }
        return $find;
    }

    private function botTemplatePageHandler($message, $query): bool
    {
        $matches = [];

        $templates = BotMenuSlug::query()
            ->with(["page"])
            ->has('page')
            ->where("bot_id", $this->getSelf()->id)
            ->get();

        $find = false;
        foreach ($templates as $template) {

            $command = $template->command;


            if (!str_starts_with($command, "/"))
                $command = "/" . $command;

            try {
                if (preg_match($command . "$/i", $query, $matches)) {
                    $this->prepareTemplatePage($template->page);


                    if (!is_null($template->page->next_bot_menu_slug_id)) {
                        $slug = BotMenuSlug::query()
                            ->where("id", $template
                                ->page
                                ->next_bot_menu_slug_id)
                            ->first();

                        $item = Collection::make($this->slugs)
                            ->where("path", $slug->slug)
                            ->first();

                        if (!is_null($item)) {

                            $this->selfScriptDiagnostic($slug);

                            $this->tryCall($item, $message,
                                $slug->config ?? null, []);

                        }
                    }

                    if (!is_null($template->page->next_bot_dialog_command_id)) {
                        $this->startBotDialog($template->page->next_bot_dialog_command_id);
                        return true;
                    }

                    if (!is_null($template->page->next_page_id)) {
                        $next = BotPage::query()
                            ->find($template->page->next_page_id);

                        $this->prepareTemplatePage($next);
                    }

                    $find = true;
                    break;
                }
            } catch (\Exception $e) {
                return $find;
            }

            if ($find)
                return true;

        }
        return $find;
    }

    /*  private function botDialogStartHandler($message, $query): bool
      {
          $matches = [];

          $templates = BotMenuSlug::query()
              ->with(["page"])
              ->whereNotNull("bot_dialog_command_id")
              ->where("bot_id", $this->getSelf()->id)
              ->get();


          foreach ($templates as $template) {

              $command = $template->command;

              if (!str_starts_with($command, "/"))
                  $command = "/" . $command;

              if (preg_match($command . "$/i", $query, $matches)) {

                  $this->startBotDialog($template->bot_dialog_command_id ?? null);
                  return true;
              }

          }
          return false;
      }*/

    private function botRouteHandler($message, $query): bool
    {
        $find = false;
        $matches = [];
        $arguments = [];

        foreach ($this->routes as $item) {

            if (is_null($item["path"]) || $item["is_service"])
                continue;

            $reg = $item["path"];

            if (!str_starts_with($reg, "/"))
                $reg = "/" . $reg;

            if (preg_match($reg . "$/i", $query, $matches)) {
                foreach ($matches as $match)
                    $arguments[] = $match;

                $find = $this->tryCall($item, $message, null, ...$arguments);
                break;
            }

        }

        return $find;
    }

    private function botNextHandler($message): bool
    {
        $find = false;
        if (!empty($this->next)) {
            foreach ($this->next as $item) {
                $find = $this->tryCall($item, $message);
            }
        }

        return $find;
    }

    private function botFallbackHandler($message): bool
    {
        $find = false;
        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] === "fallback") {
                $find = $this->tryCall($item, $message);
            }
        }
        return $find;
    }

    public function handler($domain)
    {
        $this->setApiToken($domain);

        $update = $this->bot->getWebhookUpdate();

        Log::info(print_r($update, true));

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

        $botStatus = $this->botStatusHandler();

        if ($botStatus != BotStatusEnum::Working)
            return;

        if ($this->currentBotUserInDialog()) {
            $this->nextBotDialog($query);
            return;
        }

        $coords = !isset($update["message"]["location"]) ? null :
            (object)[
                "latitude" => $update["message"]["location"]["latitude"] ?? 0,
                "longitude" => $update["message"]["location"]["longitude"] ?? 0
            ];

        if ($this->botLocationHandler($coords, $message))
            return;

        if ($this->botTemplatePageHandler($message, $query))
            return;

        /*   if ($this->botDialogStartHandler($message, $query))
               return;*/

        if ($this->botSlugHandler($message, $query))
            return;

        if ($this->botRouteHandler($message, $query))
            return;

        if ($this->botNextHandler($message))
            return;

        if ($this->botFallbackHandler($message))
            return;

        $this->reply("Ошибка обработки данных!");
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
