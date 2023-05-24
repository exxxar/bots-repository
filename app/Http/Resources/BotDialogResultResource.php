<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotDialogResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_user_id' => $this->bot_user_id,
            'bot_dialog_command_id' => $this->bot_dialog_command_id,
            'current_input_data' => $this->current_input_data,
            'summary_input_data' => $this->summary_input_data,
            'completed_at' => $this->completed_at,
        ];
    }
}
