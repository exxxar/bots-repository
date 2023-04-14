<?php

namespace App\Facades;


use App\Classes\BotManager as Service;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Service bot()
 * @see \Illuminate\Log\Logger
 */
class BotManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bot.manager';
    }
}
