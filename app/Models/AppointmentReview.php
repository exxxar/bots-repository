<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentReview extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_event_id',
        'appointment_schedule_id',
        'bot_user_id',
        'rating',
        'text',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'appointment_schedule_id' => 'integer',
        'appointment_event_id' => 'integer',
        'bot_user_id' => 'integer',
    ];

    protected $with = ["schedule","botUser"];


    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AppointmentSchedule::class,"appointment_schedule_id","id");
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class,"bot_user_id","id");
    }

    public function appointmentEvent(): BelongsTo
    {
        return $this->belongsTo(AppointmentEvent::class,"appointment_event_id","id");
    }
}
