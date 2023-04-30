<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\AdminBotController;

BotManager::bot()
    ->controller(AdminBotController::class)
    ->slug("slug_news_1", "getNews")
    ->slug("slug_admin_menu_1", "getBotAdminMenu");
