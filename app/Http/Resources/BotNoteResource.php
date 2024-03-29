<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotNoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'bot_id' => $this->bot_id ?? null,
            'bot_user_id' => $this->bot_user_id ?? null,
            'text' => $this->text ?? null,
        ];
    }
}
