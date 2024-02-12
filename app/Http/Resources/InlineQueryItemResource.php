<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InlineQueryItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'inline_query_slug_id' => $this->inline_query_slug_id,
            'inline_slug' => $this->whenLoaded("inlineSlug"),
            'type' => $this->type,
            'title' => $this->title,
            'description' => $this->description,
            'input_message_content' => $this->input_message_content,
            'inline_keyboard_id' => $this->inline_keyboard_id,
            'inline_keyboard' => $this->whenLoaded("inlineKeyboard"),
            'custom_settings' => $this->custom_settings,
        ];
    }
}
