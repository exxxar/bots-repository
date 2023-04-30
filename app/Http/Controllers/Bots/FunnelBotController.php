<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;

class FunnelBotController extends Controller
{
    //

    public function maninMenu()
    {

        $botUser = BotManager::bot()
            ->currentBotUser();

        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                !$botUser->is_vip ?
                    "main_menu_cashman_funnel_1" :
                    "main_menu_cashman_funnel_2");
    }

    public function form()
    {
        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                    "main_menu_cashman_funnel_2");
    }

    public function text1()
    {
        BotManager::bot()
            ->reply("text1");
    }

    public function text2()
    {
        BotManager::bot()
            ->reply("text2");
    }


    public function text3()
    {
        BotManager::bot()
            ->reply("text3");
    }

    public function text4()
    {
        BotManager::bot()
            ->reply("text4");
    }


    public function myCompanies()
    {
        BotManager::bot()
            ->reply("myCompanies");
    }


    public function myBots()
    {
        BotManager::bot()
            ->reply("myBots");
    }


    public function support()
    {
        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                "main_menu_cashman_funnel_3");
    }

    public function callback()
    {
        BotManager::bot()
            ->reply("callback");
    }


    public function about()
    {
        BotManager::bot()
            ->reply("about");
    }


}
