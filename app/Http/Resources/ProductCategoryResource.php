<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'title' => $this->title,
            'bot_id' => $this->bot_id,
            'is_active' => $this->is_active,
            'order_position' => $this->order_position ?? 0,
            'count' => $this->count ?? 0,
            'products' => $this->whenLoaded('products'),
        ];
    }
}
