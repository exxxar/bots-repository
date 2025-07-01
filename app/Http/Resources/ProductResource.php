<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'article' => $this->article,
            'vk_product_id' => $this->vk_product_id,
            'frontpad_article' => $this->frontpad_article,
            'iiko_article' => $this->iiko_article,
            'title' => $this->title,
            'description' => $this->description,
            'delivery_terms' => $this->delivery_terms,
            'dimension' => $this->dimension,
            'images' => $this->images,
            'type' => $this->type,
            'old_price' => $this->old_price,
            'current_price' => $this->current_price,
            'variants' => $this->variants,
            'in_stop_list_at' => $this->in_stop_list_at,
            'bot_id' => $this->bot_id,
            'rating' => $this->rating ?? 5,
            'reviews' => $this->reviews ?? [],
            'deleted_at' => $this->deleted_at ?? null,
            "options"=> ProductOptionResource::collection($this->whenLoaded('productOptions')),
            'categories' => ProductCategoryResource::collection($this->whenLoaded('productCategories')),
        ];
    }
}
