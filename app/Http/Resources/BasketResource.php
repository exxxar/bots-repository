<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BasketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'count' => $this->count,
            'bot_user_id' => $this->bot_user_id,
            'bot_id' => $this->bot_id,
            'ordered_at' => $this->ordered_at,
        ];
    }
}
