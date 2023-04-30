<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\ManagerBotController;


BotManager::bot()
    ->controller(ManagerBotController::class)
    ->slug("slug_cashman_manager_main_menu_1", "main")
    ->slug("slug_cashman_manager_form_1", "form")
    ->slug("slug_cashman_manager_bot_list_1", "botList")
    ->slug("slug_cashman_manager_profile_1", "profile")
    ->slug("slug_cashman_manager_study_1", "study")
    ->slug("slug_cashman_manager_bots_type_1_1", "botType1")
    ->slug("slug_cashman_manager_bots_type_2_1", "botType2")
    ->slug("slug_cashman_manager_bots_type_3_1", "botType3")
    ->slug("slug_cashman_manager_bots_type_4_1", "botType4")
    ->slug("slug_cashman_manager_requests_1", "requests")
    ->slug("slug_cashman_manager_watchers_1", "watchers")
    ->slug("slug_cashman_manager_about_us_1", "aboutUs")
    ->slug("slug_cashman_manager_about_bot_1", "aboutBot");

