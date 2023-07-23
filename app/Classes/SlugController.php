<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

abstract class SlugController extends Controller
{
    //protected $bot;

    public function __construct()
    {

        //$this->bot = BotManager::bot()->getSelf();

    }

    protected abstract function config();

}
