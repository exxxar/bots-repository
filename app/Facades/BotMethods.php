<?php

namespace App\Facades;


use App\Classes\BotMethods as Service;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Service bot()
 * @method static string prepareUserName($botUser)
 * @see \Illuminate\Log\Logger
 */
class BotMethods extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bot.methods';
    }
}
