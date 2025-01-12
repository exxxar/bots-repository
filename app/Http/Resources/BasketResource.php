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
            'product' => $this->whenLoaded('product') ?? null,
            'product_collection_id' => $this->product_collection_id,
            'collection' => $this->whenLoaded('collection') ?? null,
            'count' => $this->count,
            'params' => $this->params ?? null,
            'bot_user_id' => $this->bot_user_id,
            'bot_id' => $this->bot_id,
            'table_id' => $this->table_id,
            'table_approved_at' => $this->table_approved_at,
            'ordered_at' => $this->ordered_at,
        ];
    }
}
