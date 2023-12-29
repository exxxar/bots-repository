<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_event_id' => $this->appointment_event_id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'images' => $this->images,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'need_prepayment' => $this->need_prepayment,
            'appointments' => AppointmentCollection::make($this->whenLoaded('appointments')),
            'appointmentEvents' => AppointmentEventCollection::make($this->whenLoaded('appointmentEvents')),
        ];
    }
}
