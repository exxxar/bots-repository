<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'is_template' => $this->is_template ?? false,
            'template_description' => $this->template_description ?? null,
            'company_id' => $this->company_id,
            'bot_domain' => $this->bot_domain,
            'welcome_message' => $this->welcome_message,
            'bot_token' => $this->bot_token ?? null,
            'bot_token_dev' => $this->bot_token_dev?? null,
            'order_channel' => $this->order_channel,
            'main_channel' => $this->main_channel,
            'message_threads' => $this->message_threads ?? null,
            'vk_shop_link' => $this->vk_shop_link ?? null,
            'callback_link' => $this->callback_link ?? null,
            'maintenance_message' => $this->maintenance_message,
            'balance' => $this->balance,
            'tax_per_day' => $this->tax_per_day,
            'image' => $this->image,
            'description' => $this->description,
            'info_link' => $this->info_link,
            'social_links' => $this->social_links,
            'is_active' => $this->is_active,
            'auto_cashback_on_payments' => $this->auto_cashback_on_payments ?? false,
            'bot_type_id' => $this->bot_type_id,
            'level_1' => $this->level_1,
            'level_2' => $this->level_2,
            'level_3' => $this->level_3,
            'warnings' => BotWarningResource::collection($this->whenLoaded("warnings")) ,
            'blocked_message' => $this->blocked_message,
            'payment_provider_token' => $this->payment_provider_token ?? null,
            'amo' => new AmoCrmResource($this->whenLoaded("amo")),
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'company' => new CompanyResource($this->whenLoaded('company')),
            'imageMenus' => ImageMenuResource::collection($this->whenLoaded('imageMenus')),
            //'productCategories' => ProductCategoryCollection::make($this->whenLoaded('productCategories')),
        ];
    }
}
