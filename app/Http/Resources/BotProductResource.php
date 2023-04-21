<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'images' => $this->images,
            'base_price' => $this->base_price,
            'discount_price' => $this->discount_price,
            'weight' => $this->weight,
            'count' => $this->count,
            'in_stock' => $this->in_stock,
            'specifications' => $this->specifications,
            'variants' => $this->variants,
            'owner_id' => $this->owner_id,
            'bot_id' => $this->bot_id,
            'botProductCategories' => BotProductCategoryCollection::make($this->whenLoaded('botProductCategories')),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
