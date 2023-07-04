<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotMenuSlugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'config' => $this->config ?? null,
            'is_global' => $this->is_global ?? false,
            'command' => $this->command,
            'comment' => $this->comment,
            'slug' => $this->slug,
            'page' => !is_null($this->page),
            'deprecated_at' => $this->deprecated_at ?? null,

        ];
    }
}
