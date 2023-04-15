<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotMenuTemplate;
use Illuminate\Database\Seeder;

class BotMenuTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bots = Bot::query()
            ->get();

        foreach ($bots as $bot) {
            BotMenuTemplate::query()->create([
                'bot_id' => $bot->id,
                'type' => 'reply',
                'slug' => "main_menu_restaurant_1",
                'menu' => [
                    [
                        ["text" => "\xF0\x9F\x93\x8DАнкета VIP-пользователя"],
                    ],
                    [
                        ["text" => "\xF0\x9F\x94\x8DЛокация"],
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
    }
}
