<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'code' => $this->code,
            'description' => $this->description,
            'slot_amount' => $this->slot_amount,
            'scripts' => $this->scripts ?? [],
            'config' => $this->config ?? [],
            'cashback_amount' => $this->cashback_amount,
            'max_activation_count' => $this->max_activation_count,
            'current_activation_count' => count($this->botUsers ?? []),
            'is_active' => $this->is_active,
            'available_to' => $this->available_to ?? null,
            'activate_price' => $this->activate_price ?? 0,
            //'botUsers' => BotUserCollection::make($this->whenLoaded('botUsers')),
        ];
    }
}
