<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\Web\AdminBotController;

BotManager::bot()
    ->controller(AdminBotController::class)
    ->route("/adminmenu", "getBotAdminMenu2")
    ->route("/adminmenu2", "getBotAdminMenu2")
    ->route("/adminmenuold", "getBotAdminMenu")
    ->route("/admindemo", "getBotAdminMenuDemo")
    ->slug("slug_news_1", "getNews")
    ->slug("slug_admin_menu_1", "getBotAdminMenu");
