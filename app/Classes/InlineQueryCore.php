<?php

namespace App\Classes;

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

        //$id = $data["id"] ?? null;
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