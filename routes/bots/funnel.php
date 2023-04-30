<?php

use App\Facades\BotManager;
use App\Http\Controllers\Bots\FunnelBotController;


BotManager::bot()
    ->controller(FunnelBotController::class)
    ->slug("slug_funnel_main_menu_1", "maninMenu")
    ->slug("slug_funnel_form_1", "form")
    ->slug("slug_funnel_t_1_1", "text1")
    ->slug("slug_funnel_t_2_1", "text2")
    ->slug("slug_funnel_t_3_1", "text3")
    ->slug("slug_funnel_t_4_1", "text4")
    ->slug("slug_funnel_my_companies_1", "myCompanies")
    ->slug("slug_funnel_my_bots_1", "myBots")
    ->slug("slug_funnel_support_1", "support")
    ->slug("slug_funnel_callback_1", "callback")
    ->slug("slug_funnel_about_1", "about");

