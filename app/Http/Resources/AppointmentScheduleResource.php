<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'appointment_event_id' => $this->appointment_event_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'day' => $this->day,
        ];
    }
}
