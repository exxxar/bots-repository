<?php

namespace App\Providers;

use App\Classes\BotManager;
use App\Classes\BotMethods;
use App\Classes\InlineQueryCore;
use App\Classes\StartCodesCore;
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
        $this->app->bind('codes.service', fn () => new StartCodesCore());
        $this->app->bind('inline.query.service', fn () => new InlineQueryCore());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
