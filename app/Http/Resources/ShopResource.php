<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'title' => $this->title,
            'image' => $this->image,
            'key' => $this->key,
            'is_default' => $this->is_default,
            'config' => $this->config,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
