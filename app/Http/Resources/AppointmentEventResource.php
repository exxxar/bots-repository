<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentEventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'description' => $this->description,
            'services' => $this->whenLoaded("services"),
            'times' => $this->whenLoaded("times"),
            'images' => $this->images ?? [],
            'is_group' => $this->is_group ?? false,
            'max_people' => $this->max_people ?? 0,
            'min_people' => $this->min_people ?? 0,
            'on_start_appointment' => $this->on_start_appointment,
            'on_cancel_appointment' => $this->on_cancel_appointment,
            'on_after_appointment' => $this->on_after_appointment,
            'on_repeat_appointment' => $this->on_repeat_appointment,
        ];
    }
}
