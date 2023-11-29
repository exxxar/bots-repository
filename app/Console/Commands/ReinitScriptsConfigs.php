<?php

namespace App\Console\Commands;

use App\Classes\SlugController;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\Bot;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ReinitScriptsConfigs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:reinit-scripts-configs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ReInit Script Configs';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        include_once base_path('routes/bot.php');
        $bot = Bot::query()
            ->where("bot_domain", env("AUTH_BOT_DOMAIN"))
            ->first();

        if (is_null($bot)) {
            $this->info('Бот авторизации не найден в системе');
            return;
        }

        $tmp = [];
        foreach (BotManager::bot()->getRoutes() as $route) {
            $action = $route;
            if (array_key_exists('controller', $action)) {
                $controller = $action["controller"];

                if (is_subclass_of($controller, SlugController::class) && !in_array($controller, $tmp)) {
                    $this->info('Обрабатываем контроллер=>' . ($controller ?? '-'));
                    $tmp[] = $controller;
                    app($controller)->config($bot);
                }
            }
        }
    }
}
