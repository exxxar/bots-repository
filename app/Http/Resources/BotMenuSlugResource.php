<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotMenuSlugResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'command' => $this->command,
            'comment' => $this->comment,
            'bot_dialog_command_id' => $this->bot_dialog_command_id,
            'bot_dialog_command' => $this->whenLoaded("botDialogCommand"),
            'slug' => $this->slug,
            'page' => !is_null($this->page)
        ];
    }
}
