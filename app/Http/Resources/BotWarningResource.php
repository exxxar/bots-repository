<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BotWarningResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ?? null,
            'bot_id' => $this->bot_id,
            'rule_key' => $this->rule_key,
            'rule_value' => $this->rule_value,
            'is_active' => $this->is_active,
        ];
    }
}
