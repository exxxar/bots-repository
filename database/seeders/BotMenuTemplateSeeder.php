<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Illuminate\Database\Seeder;

class BotMenuTemplateSeeder extends Seeder
{
    protected function handlerCashBackMenu($bot){
        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_restaurant_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DАнкета пользователя"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DНаши заведения"],
                ],
                [
                    ["text" => "\xF0\x9F\x91\xAAМеню"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91О нас"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_restaurant_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DЛокация"],
                ],
                [
                    ["text" => "\xF0\x9F\x91\xAAМеню"],
                    ["text" => "\xF0\x9F\x91\xAAПригласить друзей"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91О нас"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_restaurant_3",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x92\xB0Special CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DЛокация"],
                ],
                [
                    ["text" => "\xF0\x9F\x8E\xABМеню"],
                    ["text" => "\xF0\x9F\x8E\xADПригласить друзей"],
                ],
                [
                    ["text" => "\xF0\x9F\x8E\xA6О нас"],
                    ["text" => "\xF0\x9F\x92\xACО боте"],
                ],
                [
                    ["text" => "\xF0\x9F\x8E\x93Админ.меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_restaurant_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DМой бюджет"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DЗапросить CashBack"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_restaurant_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DПозвать официанта"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DКорзина"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DТовары по категориям"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_restaurant_3",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DКорзина"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DТовары по категориям"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_restaurant_5",
            'menu' => [
                [
                    ["text" => "\xE2\x98\x95Найти друзей!"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DМои друзья"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_restaurant_4",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DНаши заведения"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_3_restaurant_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DМеню"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DЗабронировать столик"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_3_restaurant_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DНачисления"],
                    ["text" => "\xF0\x9F\x93\x8DСписания"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DБлаготворительность"],

                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "booking_table_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x8E\xB2Указать столик для бронирования", "web_app" => [
                        "url" => env("APP_URL") . "/restaurant/active-admins/$bot->bot_domain"
                    ]],
                ],

            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "vip_form_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету", "web_app" => [
                        "url" => env("APP_URL") . "/restaurant/vip-form/$bot->bot_domain"
                    ]],
                ],

            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "cashback_ask_admin_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x8E\xB2Пригласить администратора", "web_app" => [
                        "url" => env("APP_URL") . "/restaurant/active-admins/$bot->bot_domain"
                    ]],
                ],

            ],
        ]);
    }

    protected function handlerShopMenu($bot){
        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_shop_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DАнкета пользователя"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DНаши заведения"],
                    ["text" => "\xF0\x9F\x94\x8DСпециальные предлжожения"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DМагазин товаров"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91О нас"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_shop_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x91\xAAПригласить друзей"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DМагазин товаров"],
                    ["text" => "\xF0\x9F\x91\xAAМои заказы"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DНаши заведения"],
                    ["text" => "\xF0\x9F\x94\x8DСпециальные предлжожения"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91О нас"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ]

            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_shop_3",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x91\xAAПригласить друзей"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DМагазин товаров"],
                    ["text" => "\xF0\x9F\x91\xAAМои заказы"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DНаши заведения"],
                    ["text" => "\xF0\x9F\x94\x8DСпециальные предлжожения"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91О нас"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DАдмин.панель"],
                ],

            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_shop_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DМой бюджет"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DЗапросить CashBack"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_2_shop_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DКорзина товаров"],
                    ["text" => "\xF0\x9F\x93\x8DИзбранное"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DОформление заказа"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DПросмотр состояния заказов"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DИстория заказов"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "vip_form_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x8E\xB2Заполнить анкету клиента", "web_app" => [
                        "url" => env("APP_URL") . "/restaurant/vip-form/$bot->bot_domain"
                    ]],
                ],

            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "menu_level_3_shop_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DНачисления"],
                    ["text" => "\xF0\x9F\x93\x8DСписания"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DБлаготворительность"],

                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DSpecial CashBack System"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DГлавное меню"],
                ],
            ],
        ]);
    }

    protected function handlerDeliveryServiceMenu($bot){
        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_deliveryman_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DПрофиль работника"],
                    ["text" => "\xF0\x9F\x94\x8DОтчеты о работе"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x8DПоиск заказов"],
                    ["text" => "\xF0\x9F\x94\x8DМои заказы"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91Правила работы"],
                    ["text" => "\xF0\x9F\x92\xBBО боте"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_deliveryman_2",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x94\x8DОтчет о доставках"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DОтчет о доходах"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91Главное меню"],
                ],
            ],
        ]);

        BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_deliveryman_3",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x94\x8DЗаказы в работе"],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DЗавершенные заказы"],
                ],
                [
                    ["text" => "\xF0\x9F\x93\x91Главное меню"],
                ],
            ],
        ]);
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $botCashBack = Bot::query()
            ->where("bot_domain", "obedy_go_bot")
            ->first();

        $this->handlerCashBackMenu($botCashBack);

        $botShop= Bot::query()
            ->where("bot_domain", "isushibot")
            ->first();

        $this->handlerShopMenu($botShop);

        $botDeliverymanService= Bot::query()
            ->where("bot_domain", "deliveryrocketbot")
            ->first();

        $this->handlerDeliveryServiceMenu($botDeliverymanService);
    }
}
