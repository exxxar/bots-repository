<?php

namespace App\Http\BusinessLogic\Methods\Classes;

use App\Http\Resources\ShopConfigPublicResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

trait HasSettings
{
    private array $defaultConfig = [
        "self_updated" => false,
        "theme" => "/theme6.bootstrap.min.css",
        "themes" => [
            [
                'title' => 'Тема 1',
                'href' => '/theme1.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 2',
                'href' => '/theme2.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 3',
                'href' => '/theme3.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 4',
                'href' => '/theme4.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 5',
                'href' => '/theme5.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 6',
                'href' => '/theme6.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 7',
                'href' => '/theme7.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 8',
                'href' => '/theme8.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 9',
                'href' => '/theme9.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 10',
                'href' => '/theme10.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 11',
                'href' => '/theme11.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 12',
                'href' => '/theme12.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 13',
                'href' => '/theme13.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 14',
                'href' => '/theme14.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 15',
                'href' => '/theme15.bootstrap.min.css',
            ],
            [
                'title' => 'Тема 16',
                'href' => '/theme16.bootstrap.min.css',
            ],
            [
                'title' => 'Зеленая новая',
                'href' => '/dusty-green-theme.css',
            ],
            [
                'title' => 'Новогодняя',
                'href' => '/new-year-theme.css',
            ],
            [
                'title' => 'Летняя',
                'href' => '/summer-theme.css',
            ],
            [
                'title' => 'Flatly',
                'href' => '/flatly-theme.css',
            ],
            [
                'title' => 'Brite',
                'href' => '/brite-theme.min.css',
            ],
            [
                'title' => 'Litera',
                'href' => '/litera-theme.min.css',
            ],


        ],
        "init_certificate" => [
            "title" => "Подарочный сертификат",
            "description" => "500 рублей на CashBack",
            "amount" => 500,
            "type" => "cashback",
            "is_active"=>false,
        ],
        "delivery_price_text" => "Цена доставки рассчитывается курьером",
        "disabled_text" => "Временно недоступно!",
        "can_work_in_marketplace" => false,
        "min_price" => 100,
        "manager" => [
            "link"=>null,
            "title"=>null,
        ],
        "recommendation"=>[
            "categories"=>[],
            "products"=>[],
            "excludes"=>[]
        ],
        "price_per_km" => 100,
        "min_price_for_cashback" => 2000,
        "is_edit_mode" => false,
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
        "base_payment_service" => [
            "needs" => [
                "need_name" => true,
                "need_phone_number" => true,
                "need_email" => false,
                "need_shipping_address" => false,
                "send_phone_number_to_provider" => false,
                "send_email_to_provider" => false,
                "is_flexible" => false,
                "disable_notification" => false,
                "protect_content" => false,
            ],
            "checkout_title" => "Заказ товара",
            "checkout_description" => "Ваш товар"

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

    public function validateConfig(array $inputConfig): array
    {
        $normalizedConfig = [];

        $isKeyValueArray = isset($inputConfig[0]['key']) && isset($inputConfig[0]['value']);

        if ($isKeyValueArray) {
            foreach ($inputConfig as $item) {
                if (isset($item['key']) && array_key_exists('value', $item)) {
                    $normalizedConfig[$item['key']] = $item['value'];
                }
            }
        } else {
            $normalizedConfig = $inputConfig;
        }

        return $normalizedConfig;
    }

    public function getConfig(): array
    {
        if (is_null($this->bot)) {
            throw new HttpException(400, "Не заданы необходимые параметры функции");
        }

        $default = $this->getDefaultConfig();
        $config = $this->bot->config ?? [];

        $tmp = [];


        if (!is_null($config ?? null)) {

            foreach ($config ?? [] as $key => $value) {

                $tmp[$key] = is_null($value ?? null) ? ($default[$key] ?? null) : $value;
            }


            foreach ($default as $key => $item) {
                if (!isset($tmp[$key]))
                    $tmp[$key] = $item;
            }

            if (!is_null($tmp["icons"] ?? null)) {
                $tmp['icons'] = is_string($tmp['icons']) ? (array)(json_decode($tmp['icons'])) : $tmp['icons'];
                foreach ($tmp['icons'] as &$icon) {
                    foreach ($default["icons"][0] as $key => $defaultValue) {
                        if (!array_key_exists($key, (array)$icon)) {
                            $icon[$key] = $defaultValue;
                        }
                    }
                }

            }

            if (!is_null($tmp["base_payment_service"] ?? null)) {
                $tmp['base_payment_service'] = is_string($tmp['base_payment_service']) ? (array)(json_decode($tmp['base_payment_service'])) : $tmp['base_payment_service'];
            }

            if (!is_null($tmp["themes"] ?? null)) {
                $tmp['themes'] = is_string($tmp['themes']) ? (array)(json_decode($tmp['themes'])) : $tmp['themes'];
            }

            if (!is_null($tmp["manager"] ?? null)) {
                $tmp['manager'] = is_string($tmp['manager']) ? (array)(json_decode($tmp['manager'])) : $tmp['manager'];
            }

            if (!is_null($tmp["recommendation"] ?? null)) {
                $tmp['recommendation'] = is_string($tmp['recommendation']) ? (array)(json_decode($tmp['recommendation'])) : $tmp['recommendation'];
            }

        }

        // Признак админа
        $tmp['is_admin'] = !is_null($this->botUser) ? $this->botUser->is_admin || $this->botUser->is_manager : false;


        return $tmp;
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

        // Получаем текущий конфиг без дефолтов
        $config = $this->validateConfig($this->bot->config ?? []);

        foreach ($data as $key => $value) {
            $config[$key] = $value;
        }

        $config["self_updated"] = true;
        $this->bot->config = $config;
        $this->bot->save();

    }

    protected function getDefaultConfig(): array
    {
        return $this->defaultConfig;
    }


}
