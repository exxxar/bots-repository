<?php

namespace App\Facades;


use App\Classes\BotMessageService as Service;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Service query($bot)
 * @see \Illuminate\Log\Logger
 */
class BotMessages extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'bot.messages';
    }
}
