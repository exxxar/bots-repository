<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\Web\AdminBotController;

BotManager::bot()
    ->controller(AdminBotController::class)
    ->route("/adminmenu", "getBotAdminMenu")
    ->route("/admindemo", "getBotAdminMenuDemo")
    ->slug("slug_news_1", "getNews")
    ->slug("slug_admin_menu_1", "getBotAdminMenu");
