<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotMediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'bot_user_id' => $this->bot_user_id,
            'file_id' => $this->file_id,
            'caption' => $this->caption,
            'type' => $this->type,
        ];
    }
}
