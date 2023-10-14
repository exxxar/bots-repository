<?php

namespace App\Facades;


use App\Classes\StartCodesCore as Service;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Service bot()
 * @see \Illuminate\Log\Logger
 */
class StartCodesService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'codes.service';
    }
}
