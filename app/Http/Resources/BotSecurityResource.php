<?php

namespace App\Http\Resources;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotSecurityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {


        return [
            'id' => $this->id ?? null,

            'title' => $this->title ?? null,
            'short_description' => $this->short_description ?? null,
            'long_description' => $this->long_description ?? null,
            'cashback_fire_percent'=> $this->cashback_fire_percent ?? 0,
            'cashback_fire_period'=> $this->cashback_fire_period ?? 0,
            'max_cashback_use_percent'=> $this->max_cashback_use_percent ?? 0,
            'bot_domain' => $this->bot_domain,
            'settings' => $this->settings ?? null,
            'welcome_message' => $this->welcome_message,
            'image' => $this->image,
            'description' => $this->description,
            'main_channel' => $this->main_channel,
            'order_channel' => $this->order_channel,
            'cashback_config' => $this->cashback_config,
            'info_link' => $this->info_link,
            'vk_shop_link' => $this->vk_shop_link,
            'social_links' => $this->social_links,
            'bot_type_id' => $this->bot_type_id,
            'level_1' => $this->level_1,
            'level_2' => $this->level_2,
            'level_3' => $this->level_3,
            'auto_cashback_on_payments' => $this->auto_cashback_on_payments ?? false,
            'warnings' => BotWarningResource::collection($this->whenLoaded("warnings")),
            'company' => new CompanySecurityResource($this->whenLoaded('company')),
            'field_settings' => !isset($this->fieldSettings) ? null : BotCustomFieldSettingResource::collection($this->whenLoaded('fieldSettings')),
            'iiko' => new IikoResource($this->whenLoaded("iiko")),
            'frontPad' => new FrontPadResource($this->whenLoaded("frontPad")),
           // 'bitrix' => !isset($this->bitrix) ? [] : BitrixResource::collection($this->whenLoaded('bitrix')),

            //'imageMenus' => ImageMenuResource::collection($this->whenLoaded('imageMenus')),
            //'productCategories' => ProductCategoryCollection::make($this->whenLoaded('productCategories')),
        ];
    }
}
