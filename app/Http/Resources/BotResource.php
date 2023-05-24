<?php

namespace App\Http\Resources;

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
            'bot_token' => $this->bot_token,
            'bot_token_dev' => $this->bot_token_dev,
            'order_channel' => $this->order_channel,
            'main_channel' => $this->main_channel,
            'maintenance_message' => $this->maintenance_message,
            'balance' => $this->balance,
            'tax_per_day' => $this->tax_per_day,
            'image' => $this->image,
            'description' => $this->description,
            'info_link' => $this->info_link,
            'social_links' => $this->social_links,
            'is_active' => $this->is_active,
            'bot_type_id' => $this->bot_type_id,
            'level_1' => $this->level_1,
            'level_2' => $this->level_2,
            'level_3' => $this->level_3,
            'blocked_message' => $this->blocked_message,
            'blocked_at' => $this->blocked_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            //'productCategories' => ProductCategoryCollection::make($this->whenLoaded('productCategories')),
        ];
    }
}
