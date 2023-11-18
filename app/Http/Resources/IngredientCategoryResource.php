<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IngredientCategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'bot_id' => $this->bot_id,
            'food_constructor_id' => $this->food_constructor_id,
            'bots' => BotCollection::make($this->whenLoaded('bots')),
        ];
    }
}
