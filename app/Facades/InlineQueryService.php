<?php

namespace App\Facades;


use App\Classes\InlineQueryCore as Service;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Service inline()
 * @see \Illuminate\Log\Logger
 */
class InlineQueryService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'inline.query.service';
    }
}
