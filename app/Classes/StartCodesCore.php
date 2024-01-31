<?php

namespace App\Classes;

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StartCodesCore
{
    protected $bot;
    protected $botUser;

    protected $controller = null;

    protected $routes = [];

    public function bot(): static
    {
        return $this;
    }

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;


    }

    public function setBot($bot): StartCodesCore
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    public function setBotUser($botUser): StartCodesCore
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;

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

    public function controller($controller): StartCodesCore
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function handler($data): StartCodesCore
    {
        if (is_null($data))
            return $this;

        include_once base_path('routes/codes.php');

        $result = $this->regularExpressionHandler(base64_decode($data));

        return $this;

    }

    private function regularExpressionHandler($string): bool
    {
        $find = false;
        $matches = [];
        $arguments = [];

        foreach ($this->routes as $item) {

            if (is_null($item["path"]))
                continue;

            $pattern = $item["path"];

            if (preg_match_all($pattern, $string, $matches)) {

                foreach ($matches as $match)
                    $arguments[] = $match[0];

                $find = $this->tryCall($item, ...$arguments);
                break;
            }

        }

        return $find;

    }

    public function regular(string $expression, string $action): StartCodesCore
    {

        $this->routes[] = [
            "path" => $expression,
            "controller" => $this->controller ?? null,
            "function" => $action,
        ];


        return $this;
    }


}
