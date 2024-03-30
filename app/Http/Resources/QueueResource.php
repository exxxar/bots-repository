<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'content' => $this->content,
            'reply_keyboard' => $this->reply_keyboard,
            'inline_keyboard' => $this->inline_keyboard,
            'images' => $this->images,
            'sent_at' => $this->sent_at,
        ];
    }
}
