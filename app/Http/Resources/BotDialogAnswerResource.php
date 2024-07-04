<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotDialogAnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_dialog_command_id' => $this->bot_dialog_command_id,
            'answer' => $this->answer,
            'pattern' => $this->pattern?? null,
            'custom_stored_value' => $this->custom_stored_value ?? null,
            'next_bot_dialog_command_id' => $this->next_bot_dialog_command_id,
        ];
    }
}
