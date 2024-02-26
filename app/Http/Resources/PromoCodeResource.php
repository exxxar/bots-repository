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
            'cashback_amount' => $this->cashback_amount,
            'max_activation_count' => $this->max_activation_count,
            'current_activation_count' => $this->whenLoaded('botUsers') ?? 0,
            'is_active' => $this->is_active,
            //'botUsers' => BotUserCollection::make($this->whenLoaded('botUsers')),
        ];
    }
}