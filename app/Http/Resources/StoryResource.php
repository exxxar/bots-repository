<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'image' => $this->image,
            'description' => $this->description,
            'config' => $this->config,
            'bot' => BotResource::make($this->whenLoaded('bot')),
        ];
    }
}
