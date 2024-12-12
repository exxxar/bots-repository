<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FolderResource extends JsonResource
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
            'type' => $this->type,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'config' => $this->config,
            'bot' => BotResource::make($this->whenLoaded('bot')),
            'pages' => BotPageCollection::make($this->whenLoaded('botPages')),
        ];
    }
}
