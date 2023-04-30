<?php

namespace App\Http\Controllers\Bots;

use App\Facades\BotManager;
use App\Http\Controllers\Controller;

class ManagerBotController extends Controller
{
    //

    public function main(){
        $botUser = BotManager::bot()
            ->currentBotUser();

        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                !$botUser->is_vip ?
                    "main_menu_cashman_manager_1" :
                    "main_menu_cashman_manager_2");
    }

    public function form(){
        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                "main_menu_cashman_manager_3");
    }

    public function botList(){
        BotManager::bot()
            ->reply("botList");
    }

    public function profile(){
        BotManager::bot()
            ->sendReplyMenu("Главное меню",
                "main_menu_cashman_manager_4");
    }

    public function study(){
        BotManager::bot()
            ->reply("study");
    }

    public function botType1(){
        BotManager::bot()
            ->reply("botType1");
    }

    public function botType2(){
        BotManager::bot()
            ->reply("botType2");
    }

    public function botType3(){
        BotManager::bot()
            ->reply("botType3");
    }

    public function botType4(){
        BotManager::bot()
            ->reply("botType4");
    }

    public function requests(){
        BotManager::bot()
            ->reply("requests");
    }

    public function watchers(){
        BotManager::bot()
            ->reply("watchers");
    }

    public function aboutUs(){
        BotManager::bot()
            ->reply("aboutUs");
    }

    public function aboutBot(){
        BotManager::bot()
            ->reply("aboutBot");
    }

}
