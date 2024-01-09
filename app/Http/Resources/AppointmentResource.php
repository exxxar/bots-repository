<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'bot_id' => $this->bot_id,
            'bot_user_id' => $this->bot_user_id,
            'appointment_schedule_id' => $this->appointment_schedule_id,
            'status' => $this->status,
            'name' => $this->name,
            'phone' => $this->phone,
            'info' => $this->info,
            'schedule' => $this->whenLoaded('schedule') ?? null,
            'botUser' => $this->whenLoaded('botUser') ?? null,
            'services' => AppointmentServiceCollection::make($this->whenLoaded('services')),
        ];
    }
}
