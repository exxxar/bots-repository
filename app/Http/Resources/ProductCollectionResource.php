<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'owner_id' => $this->owner_id,
            'title' => $this->title,
            'image' => $this->image,
            'description' => $this->description,
            'is_public' => $this->is_public,
            'is_active' => $this->is_active,
            'discount' => $this->discount,
            'order_position' => $this->order_position,
            'config' => $this->config,
            'bot_user_id' => $this->bot_user_id,
            'products' => ProductResource::collection($this->whenLoaded('products')),
        ];
    }
}
