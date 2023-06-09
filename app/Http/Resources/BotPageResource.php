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
            'id' => $this->id ?? null,
            'slug' => $this->whenLoaded('slug'),
            'content' => $this->content ?? null,
            'images' => $this->images ?? null,
            'reply_keyboard_id' => $this->reply_keyboard_id ?? null,
            'replyKeyboard' => $this->whenLoaded('replyKeyboard'),
            'inline_keyboard_id' => $this->inline_keyboard_id ?? null,
            'inlineKeyboard' => $this->whenLoaded('inlineKeyboard'),
            'bot_id' => $this->bot_id ?? null,
            'next_page_id' => $this->next_page_id ?? null,
            'next_bot_dialog_command_id' => $this->next_bot_dialog_command_id ?? null,
            'next_bot_menu_slug_id' => $this->next_bot_menu_slug_id ?? null,
            'bot_menu_slug_id' => $this->bot_menu_slug_id ?? null,
        ];
    }
}
