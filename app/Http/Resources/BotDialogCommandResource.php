<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotDialogCommandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'pre_text' => $this->pre_text,
            'post_text' => $this->post_text,
            'error_text' => $this->error_text,
            'is_empty' => $this->is_empty ?? false,
            'bot_id' => $this->bot_id,
            'bot' => $this->whenLoaded('bot'),
            'input_pattern' => $this->input_pattern,
            'inline_keyboard_id' => $this->inline_keyboard_id,
            'reply_keyboard_id' => $this->reply_keyboard_id,
            'inline_keyboard' => $this->whenLoaded('inlineKeyboard'),
            'reply_keyboard' => $this->whenLoaded('replyKeyboard'),
            'images' => $this->images,
            'next_bot_dialog_command_id' => $this->next_bot_dialog_command_id,
            'bot_dialog_group_id' => $this->bot_dialog_group_id,
            'bot_dialog_group' => $this->whenLoaded("botDialogGroup"),
            'result_channel' => $this->result_channel,
        ];
    }
}
