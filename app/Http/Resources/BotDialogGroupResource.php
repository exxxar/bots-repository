<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotDialogGroupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'bot_id' => $this->bot_id,
            'bot_dialog_commands' => BotDialogCommandResource::collection($this->whenLoaded('botDialogCommands')),
        ];
    }
}
