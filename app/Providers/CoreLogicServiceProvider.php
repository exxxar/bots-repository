<?php

namespace App\Providers;

use App\Classes\BotManager;
use App\Classes\BotMethods;
use App\Http\BusinessLogic\BusinessLogic;
use App\Http\CoreLogic\CoreLogic;
use Illuminate\Support\ServiceProvider;

class CoreLogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('core.logic', fn () => new CoreLogic());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
