<?php

namespace App\Providers;

use App\Classes\BotManager;
use App\Classes\BotMethods;
use Illuminate\Support\ServiceProvider;

class BotManagerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('bot.manager', fn () => new BotManager());
        $this->app->bind('bot.methods', fn () => new BotMethods());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
