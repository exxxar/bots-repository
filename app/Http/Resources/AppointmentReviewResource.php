<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_event_id' => $this->appointment_event_id,
            'appointment_schedule_id' => $this->appointment_schedule_id,
            'bot_user_id' => $this->bot_user_id,
            'rating' => $this->rating,
            'text' => $this->text,
            'schedule' => $this->whenLoaded('schedule') ?? null,
            'botUser' => $this->whenLoaded('botUser') ?? null,
            'appointmentEvents' => AppointmentEventCollection::make($this->whenLoaded('appointmentEvents')),
        ];
    }
}
