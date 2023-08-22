<?php

namespace App\Providers;

use App\Classes\BotManager;
use App\Classes\BotMethods;
use App\Http\BusinessLogic\BusinessLogic;
use Illuminate\Support\ServiceProvider;

class BusinessLogicServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('business.logic', fn () => new BusinessLogic());
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
