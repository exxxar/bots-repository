<?php

namespace Database\Seeders;

use App\Models\Bot;
use App\Models\BotMenuSlug;
use Illuminate\Database\Seeder;

class BotMenuSlugSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bots = Bot::query()
            ->get();

        foreach ($bots as $bot) {

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Локация",
                'slug' => "slug_location_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Благотворительность",
                'slug' => "slug_charity_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Меню",
                'slug' => "slug_menu_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*О нас",
                'slug' => "slug_about_us_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*О боте",
                'slug' => "slug_about_bot_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Анкета VIP-пользователя",
                'slug' => "slug_vip_form_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Special CashBack System",
                'slug' => "slug_special_cashback_system_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Новости",
                'slug' => "slug_news_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Позвать официанта",
                'slug' => "slug_call_the_waiter_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Смотреть товары",
                'slug' => "slug_view_products_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Категории товаров",
                'slug' => "slug_product_categories_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Главное меню",
                'slug' => "slug_main_menu_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Мои друзья",
                'slug' => "slug_my_friends_1",
            ]);


            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Найти друзей!",
                'slug' => "slug_search_friends_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Мой бюджет",
                'slug' => "slug_my_budget_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Запросить CashBack",
                'slug' => "slug_request_cash_back_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Админ.меню",
                'slug' => "slug_admin_menu_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Моя сеть друзей",
                'slug' => "slug_network_of_friends_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Пригласить друзей",
                'slug' => "slug_invite_friends_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Наши заведения",
                'slug' => "slug_our_establishments_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Начисления",
                'slug' => "slug_charges_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Списания",
                'slug' => "slug_write_offs_1",
            ]);

            BotMenuSlug::query()->create([
                'bot_id' => $bot->id,
                'command' => ".*Забронировать столик",
                'slug' => "slug_book_a_table_1",
            ]);
        }
    }
}
