<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::now();
        $date->setISODate($this->year,$this->week, $this->day);

        return [
            'id' => $this->id,
            'appointment_event_id' => $this->appointment_event_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'day' => $this->day,
            'day_number' => $date->format('d'),

            'year' => $this->year,
            'month' => $this->month,
            'week' => $this->week,
            'week_start' =>  $date->startOfWeek()->format('d'),
            'week_end' =>  $date->endOfWeek()->format('d'),
           // 'has_appointment' => $this->has_appointment ?? false,
            'appointment' => $this->whenLoaded('appointment') ?? null,
        ];
    }
}
