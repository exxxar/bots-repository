<?php

namespace App\Classes;

use App\Facades\BotManager;
use Illuminate\Support\Facades\Log;

class InlineQueryCore
{

    protected $controller = null;

    protected $routes = [];

    public function bot(){
        return $this;
    }

    public function controller($controller): InlineQueryCore
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function handler($data): InlineQueryCore
    {
        if (is_null($data))
            return $this;

        $find = false;

        $id = $data["id"] ?? null;
        $query = $data["query"] ?? null;
      //  $offset = $data["offset"] ?? null;

        include_once base_path('routes/inline-queries.php');

        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            if ($item["path"] == $query) {
                $find = $this->tryCall($item, ...$data);
                break;
            }

        }

        if (!$find)
        {

                $button_list[] =  [
                    'type' => 'article',
                    'id' => uniqid(),
                    'title' => "УПС!",
                    'input_message_content' => [
                        'message_text' => "По вашему запросу ничего не найдено....",
                    ],

                    'thumb_url' => env("APP_URL")
                        ."/images/error.png",
                    //'url' => env("APP_URL"),
                    'description' => "Сожалеем, но на текущий момент по вашему запросу нет никакой информации! Возможно, она появится позже...",
                    'hide_url' => false
                ];


            \App\Facades\BotMethods::bot()
                ->sendAnswerInlineQuery($id, $button_list);
        }

        return $this;

    }

    public function query(string $command, string $action): InlineQueryCore
    {

        $this->routes[] = [
            "path" => $command,
            "controller" => $this->controller ?? null,
            "function" => $action,
        ];


        return $this;
    }

    private function tryCall($item, ...$arguments): bool
    {
        $find = false;
        try {
            if (is_callable($item["function"])) {
                app()->call($item["function"], $arguments);
            } else {
                app()->call((!is_null($item["controller"]) ?
                    $item["controller"] . "@" . $item["function"] :
                    $item["function"]), $arguments);
            }

            $find = true;
        } catch (\Exception $e) {
            Log::info($e->getMessage() . " " . $e->getFile() . " " . $e->getLine());
        }

        return $find;
    }

}
