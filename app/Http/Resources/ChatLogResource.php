<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'media_content' => $this->media_content,
            'content_type' => $this->content_type,
            'bot_id' => $this->bot_id,
            'form_bot_user_id' => $this->form_bot_user_id,
            'to_bot_user_id' => $this->to_bot_user_id,
        ];
    }
}
