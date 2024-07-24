<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubShopResource extends JsonResource
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
            'keyword' => $this->keyword,
            'image' => $this->image,
            'schedule' => $this->schedule,
            'config' => $this->config,
            'is_active' => $this->is_active,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
