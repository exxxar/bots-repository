<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotMenuTemplate;
use App\Models\BotPage;
use App\Models\BotType;
use App\Models\Company;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $botType = BotType::query()->create([
            'title' => "Визитка",
            'slug' => "business_card",
            'is_active' => true
        ]);

        $company = Company::query()->create([
            'title' => "BusinessCard Шаблон бота",
            'slug' => "business_card",
            'description' => "Добро пожаловать!Меня зовут CashMan. Я виртуальный помощник по созданию продающих Телеграмм-Ботов.",
            'address' => "бул. Шевченко, 13, Донецк",
            'image' => "d0451060e588ccb84087d.jpg",
            'phones' => [
                "+7(949)432-06-01",
                "+7(949)432-06-02",
                "+7(949)432-06-03",
            ],
            'links' => null,
            'email' => "inbox@your-cashman.ru",
            'schedule' => null,
            'manager' => "Егор",
            'is_active' => true,
        ]);

        $bot = Bot::query()->create([
            'is_template' => true,
            'template_description' => "Шаблон бота-визитки",
            'company_id' => $company->id,
            'bot_domain' => "business_card",
            'bot_token' => "6197194236:AAFDvk5X1_GhFg9b_ObVcZTq5SJXsre_6Yw",
            'bot_token_dev' => "6197194236:AAFDvk5X1_GhFg9b_ObVcZTq5SJXsre_6Yw",
            'order_channel' => null,
            'balance' => 10000,
            'tax_per_day' => 10,
            'image' => "cashback.png",
            'description' => "Тестовое описание бота",
            'info_link' => "https://telegra.ph/O-nas-06-02-4",
            'social_links' => [
                [
                    "title" => "Наш instagram",
                    "url" => "http://instagram.com/fastoran"
                ],
                [
                    "title" => "Наш профиль в ВК",
                    "url" => "https://vk.com/math_algo"
                ]
            ],
            'is_active' => true,
            'bot_type_id' => $botType->id,
            'level_1' => 7,
            'level_2' => 2,
            'level_3' => 1,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => "/start",
            'slug' => "slug_business_card_start_1",
            'comment' => "Скрипт запуска меню визитки"
        ]);

        $menu = BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'reply',
            'slug' => "main_menu_business_card_1",
            'menu' => [
                [
                    ["text" => "\xF0\x9F\x93\x8DИнформация обо мне"
                    ],
                ],
                [
                    ["text" => "\xF0\x9F\x94\x8DМои контактные данные"],
                    ["text" => "\xF0\x9F\x94\x8DПоделиться контактами"],
                ],
                [
                    ["text" => "\xF0\x9F\x91\xAAСистема лояльности CashBack"],
                    ["text" => "\xF0\x9F\x91\xAAЗаказать чат-бот Визитку"],
                ]
            ],
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Тестовый контент",
            'images' => null,
            'reply_keyboard_id' => $menu->id,
            'bot_id' => $bot->id,
        ]);


        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Главное меню",
            'slug' => "slug_business_card_start_2",
            'comment' => "Скрипт запуска меню визитки"
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Тестовый контент",
            'images' => null,
            'reply_keyboard_id' => $menu->id,
            'bot_id' => $bot->id,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Система лояльности CashBack",
            'slug' => "slug_loyalty_system_1",
            'comment' => "Скрипт выводит информацию о системе лояльности"
        ]);

        $menu = BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "main_menu_business_card_get_1",
            'menu' => [
                [
                    [
                        "text" => "\xF0\x9F\x93\x8DПолучить бот-визитку",
                        "url" => "https://t.me/vizitkanext_bot"
                    ],
                ],

            ],
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Тестовый контент",
            'images' => null,
            'inline_keyboard_id' => $menu->id,
            'bot_id' => $bot->id,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Мои контактные данные",
            'slug' => "slug_my_contacts_1",
            'comment' => "Скрипт выводит контактную информацию о владельце"
        ]);

        $menu = BotMenuTemplate::query()->create([
            'bot_id' => $bot->id,
            'type' => 'inline',
            'slug' => "main_menu_business_socials_1",
            'menu' => [
                [
                    [
                        "text" => "\xF0\x9F\x93\x8DВконтакте",
                        "url" => "https://vk.com/test"
                    ],
                ],
                [
                    [
                        "text" => "\xF0\x9F\x93\x8DИнста",
                        "url" => "https://instagram.com/test"
                    ],
                ],
                [
                    [
                        "text" => "\xF0\x9F\x93\x8DFacebook",
                        "url" => "https://facebook.com/test"
                    ],
                ],

            ],
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Мои контактные данные",
            'images' => null,
            'bot_id' => $bot->id,
            'inline_keyboard_id' => $menu->id,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Поделиться контактом",
            'slug' => "slug_share_contact_1",
            'comment' => "Скрипт позволяет поделиться контактом"
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Поделиться контактом",
            'images' => null,
            'bot_id' => $bot->id,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Информация обо мне",
            'slug' => "slug_self_info_1",
            'comment' => "Скрипт выводит информацию о владельце бота"
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Информация обо мне",
            'images' => null,
            'bot_id' => $bot->id,
        ]);

        $slug = BotMenuSlug::query()->create([
            'bot_id' => $bot->id,
            'command' => ".*Заказать чат-бот Визитку",
            'slug' => "slug_get_chat_bot_1",
            'comment' => "Скрипт позволяет вывести форму заказа бота"
        ]);

        BotPage::query()->create([
            'bot_menu_slug_id' => $slug->id,
            'content' => "Заказать чат-бот Визитку",
            'images' => null,
            'bot_id' => $bot->id,
        ]);

    }
}
