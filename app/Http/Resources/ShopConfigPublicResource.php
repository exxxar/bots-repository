<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopConfigPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {

        $isAdmin = $this->is_admin ?? false;
        if (!is_null($this->sbp ?? null)) {
            $tmpSbp = json_decode(json_encode($this->sbp));

            $sbp = (object)[
                "selected_sbp_bank" => $tmpSbp->selected_sbp_bank ?? "tinkoff",
                "tinkoff" => (object)[
                    "terminal_key" => $isAdmin ? $tmpSbp->tinkoff->terminal_key ?? null : null,
                    "terminal_password" => $isAdmin ? $tmpSbp->tinkoff->terminal_password ?? null : null,
                    "tax" => $tmpSbp->tinkoff->tax ?? null,
                    "vat" => $tmpSbp->tinkoff->vat ?? null,

                ]
            ];
        }
        $tmpManager = (object)[
            "link" => $this->manager->link ?? $this->manager["link"] ?? null,
            "title" => $this->manager->title ?? $this->manager['title'] ?? null
        ];


        return [
            "sbp" => $sbp ?? (object)[
                    "selected_sbp_bank" => "tinkoff",
                    "tinkoff" => (object)[
                        "terminal_key" => null,
                        "terminal_password" => null,
                        "tax" => null,
                        "vat" => null,

                    ]
                ],
            "delivery_price_text" => $this->delivery_price_text ?? null,
            "min_base_delivery_price" => $this->min_base_delivery_price ?? 0,
            "shop_coords" => $this->shop_coords ?? "0,0",
            "disabled_text" => $this->disabled_text ?? null,
            "min_price" => $this->min_price ?? 100,
            "price_per_km" => $this->price_per_km ?? 100,
            "min_price_for_cashback" => $this->min_price_for_cashback ?? 2000,
            "is_disabled" => $this->is_disabled ?? false,
            "can_use_card" => $this->can_use_card ?? false,
            "can_use_cash" => $this->can_use_cash ?? true,
            "can_buy_after_closing" => $this->can_buy_after_closing ?? true,
            "menu_list_type" => $this->menu_list_type ?? 0,
            "max_tables" => $this->max_tables ?? 0,
            "need_table_list" => $this->need_table_list ?? false,
            "need_category_by_page" => $this->need_category_by_page ?? true,
            "need_hide_disabled_products" => $this->need_hide_disabled_products ?? false,
            "need_hide_delivery_period" => $this->need_hide_delivery_period ?? false,

            "need_pay_after_call" => $this->need_pay_after_call ?? true,
            "is_product_list" => $this->is_product_list ?? false,
            "need_promo_code" => $this->need_promo_code ?? true, //true,
            "need_automatic_delivery_request" => $this->need_automatic_delivery_request ?? true, //true,
            "need_person_counter" => $this->need_person_counter ?? true, //true,
            "need_bonuses_section" => $this->need_bonuses_section ?? true, //true,
            "need_health_restrictions" => $this->need_health_restrictions ?? true, //true,
            "need_prizes_from_wheel_of_fortune" => $this->need_prizes_from_wheel_of_fortune ?? true, //true,
            "selected_script_id" => $this->selected_script_id ?? null, //null,
            "can_use_sbp" => $this->can_use_sbp ?? false, //false,
            "free_shipping_starts_from" => $this->free_shipping_starts_from ?? 0, //0,
            "shop_display_type" => $this->shop_display_type ?? 0, //0,
            "payment_info" => $this->payment_info ?? null, //Текст не найден",
            "wheel_of_fortune" => $this->wheel_of_fortune ?? null, //null,
            "win_message" => $this->win_message ?? null, //null
            "manager" => $tmpManager ?? null, //null

        ];
    }
}
