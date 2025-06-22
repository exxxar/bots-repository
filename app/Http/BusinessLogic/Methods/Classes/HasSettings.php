<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Http\Resources\ShopConfigPublicResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HasSettings
{
    private array $defaultConfig = [
        "delivery_price_text1" => "Цена доставки рассчитывается курьером",
        "delivery_price_text" => "Цена доставки рассчитывается курьером",
        "disabled_text" => "Временно недоступно!",
        "min_price" => 100,
        "price_per_km" => 100,
        "min_price_for_cashback" => 2000,
        "is_disabled" => false,
        "can_use_card" => false,
        "can_use_cash" => true,
        "can_buy_after_closing" => true,
        "min_base_delivery_price" => 0,
        "menu_list_type" => 0,
        "max_tables" => 0,
        "shop_coords" => "0,0",
        "need_table_list" => false,
        "need_category_by_page" => true,
        "need_pay_after_call" => true,
        "is_product_list" => false,
        "need_promo_code" => true,
        "need_automatic_delivery_request" => true,
        "need_person_counter" => true,
        "need_bonuses_section" => true,
        "need_health_restrictions" => true,
        "need_prizes_from_wheel_of_fortune" => true,
        "selected_script_id" => null,
        "payment_token" => null,
        "need_hide_disabled_products" => false,
        "need_hide_delivery_period" => false,
        "can_use_sbp" => false,
        "icons" => [
                [
                    'slug' => 'profile',
                    'title' => 'Профиль',
                    'image_url' => 'profile.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'shop',
                    'title' => 'Магазин',
                    'image_url' => 'shop.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'basket',
                    'title' => 'Корзина',
                    'image_url' => 'basket.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'history',
                    'title' => 'История заказов',
                    'image_url' => 'history.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'events',
                    'title' => 'Розыгрыши',
                    'image_url' => 'events.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'about',
                    'title' => 'О Нас & Контакты',
                    'image_url' => 'contacts.png',
                    'is_visible' => true,
                    'has_icon' => true,
                ],
                [
                    'slug' => 'wheel_of_fortune_btn',
                    'title' => 'Колесо фортуны',
                    'is_visible' => true,
                    'has_icon' => false,

                ],
                [
                    'slug' => 'friends_btn',
                    'title' => 'Друзья',
                    'is_visible' => true,
                    'has_icon' => false,
                ],
                [
                    'slug' => 'main_menu_btn',
                    'title' => 'Главное меню',
                    'is_visible' => true,
                    'has_icon' => false,
                ],
        ],
        "sbp" => [
            "selected_sbp_bank" => "tinkoff",
            "tinkoff" => [
                "terminal_key" => null,
                "terminal_password" => null,
                "tax" => null,
                "vat" => null,
            ],
            "sber" => null
        ],
        "free_shipping_starts_from" => 0,
        "shop_display_type" => 0,
        "payment_info" => "Текст не найден",
        "wheel_of_fortune" => [
            "rules" => "Правила колеса фортуны",
            "can_play" => false,
            "items" => []
        ],
        "win_message" => "{{name}}, вы приняли участие в розыгрыше и выиграли приз {{prize}}. Наш менеджер свяжется с вами в ближайшее время!",
    ];

    public function getConfig(): array
    {
        if (is_null($this->bot)) {
            throw new HttpException(400, "Не заданы необходимые параметры функции");
        }

        $default = $this->getDefaultConfig();
        $config = $this->bot->config ?? [];

        // Объединение с дефолтом
        $merged = array_merge($default, $config);

        // Признак админа
        $merged['is_admin'] = !is_null($this->botUser) ? $this->botUser->is_admin || $this->botUser->is_manager : false;

        return $merged;
    }

    public function setConfigValue(string $key, mixed $value): void
    {
        if (is_null($this->bot) || is_null($this->botUser)) {
            throw new HttpException(400, "Не заданы необходимые параметры функции");
        }

        $config = $this->bot->config ?? [];
        $config[$key] = $value;

        $this->bot->config = $config;
        $this->bot->save();
    }

    public function setConfig(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser)) {
            throw new HttpException(400, "Не заданы необходимые параметры функции");
        }

        $config = $this->bot->config ?? [];

        foreach ($data as $key => $value) {
            $config[$key] = $value;
        }

        $this->bot->config = $config;
        $this->bot->save();
    }

    protected function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }


}
