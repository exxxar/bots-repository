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
            'videos' => $this->videos ?? null,
            'audios' => $this->audios ?? null,
            'documents' => $this->documents ?? null,
            'reply_keyboard_title' => $this->reply_keyboard_title ?? null,
            'reply_keyboard_id' => $this->reply_keyboard_id ?? null,
            'replyKeyboard' => $this->whenLoaded('replyKeyboard'),
            'inline_keyboard_id' => $this->inline_keyboard_id ?? null,
            'inlineKeyboard' => $this->whenLoaded('inlineKeyboard'),
            'bot_id' => $this->bot_id ?? null,
            'is_external' => $this->is_external ?? false,
            'next_page_id' => $this->next_page_id ?? null,
            'next_bot_dialog_command_id' => $this->next_bot_dialog_command_id ?? null,
            'next_bot_menu_slug_id' => $this->next_bot_menu_slug_id ?? null,
            'bot_menu_slug_id' => $this->bot_menu_slug_id ?? null,

            'rules_if' => $this->rules_if ?? null,
            'rules_else_page_id' => $this->rules_else_page_id ?? null,

            'rules_if_message'=> $this->rules_if_message ?? null,
            'rules_else_message'=> $this->rules_else_message ?? null,
            'deleted_at'=> $this->deleted_at ?? null,
        ];
    }
}
