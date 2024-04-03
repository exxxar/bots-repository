<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_event_id',
        'start_time',
        'end_time',
        'day',
        'year',
        'month',
        'week',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'appointment_event_id' => 'integer',
        'day'=> 'integer',
        'year'=> 'integer',
        'month'=> 'integer',
        'week'=> 'integer',
    ];

   // protected $appends = ["has_appointment"];
   // protected $with = ["appointment"];

    public function appointmentEvent(): BelongsTo
    {
        return $this->belongsTo(AppointmentEvent::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class,"appointment_schedule_id","id");
    }

    public function getHasAppointmentAttribute(){
        return true;//!is_null($this->appointment());
    }

}
