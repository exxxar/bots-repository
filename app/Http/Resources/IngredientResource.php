<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image,
            'weight' => $this->weight,
            'price' => $this->price,
            'bot_id' => $this->bot_id,
            'food_constructor_id' => $this->food_constructor_id,
            'sub' => $this->sub,
            'ingredient_category_id' => $this->ingredient_category_id,
            'is_checked' => $this->is_checked,
            'is_disabled' => $this->is_disabled,
            'is_global' => $this->is_global,
        ];
    }
}
