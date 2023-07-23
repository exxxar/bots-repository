<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;

abstract class SlugController extends Controller
{
    protected $bot;

    public function __construct()
    {
        $this->bot = BotManager::bot()->getSelf();
    }

    protected abstract function handler();

    protected function getClassName(){
        return static::class;
    }
}
