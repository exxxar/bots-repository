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
            'rating' => $this->rating,
            'images' => $this->images,
            'text' => $this->text,
            'appointmentEvents' => AppointmentEventCollection::make($this->whenLoaded('appointmentEvents')),
        ];
    }
}
