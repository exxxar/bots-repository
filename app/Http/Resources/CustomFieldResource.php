<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomFieldResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'bot_user_id' => $this->bot_user_id,
            'bot_custom_field_setting_id' => $this->bot_custom_field_setting_id,
        ];
    }
}
