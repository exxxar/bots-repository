<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotMenuTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'type' => $this->type,
            'command' => $this->command,
            'slug' => $this->slug,
            'menu' => $this->menu,
            'settings' => $this->settings ?? [],
            'deleted_at' => $this->deleted_at ?? null,
        ];
    }
}
