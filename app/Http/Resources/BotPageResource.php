<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotPageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_menu_slug_id' => $this->bot_menu_slug_id,
            'slug' => $this->whenLoaded('slug'),
            'content' => $this->content,
            'images' => $this->images,
            'reply_keyboard_id' => $this->reply_keyboard_id,
            'replyKeyboard' => $this->whenLoaded('replyKeyboard'),
            'inline_keyboard_id' => $this->inline_keyboard_id,
            'inlineKeyboard' => $this->whenLoaded('inlineKeyboard'),
            'bot_id' => $this->bot_id,
            'next_page_id' => $this->next_page_id ?? null,
        ];
    }
}
