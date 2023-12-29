<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AppointmentReview extends Model
{
    use HasFactory;

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

    public function appointmentEvents(): BelongsToMany
    {
        return $this->belongsToMany(AppointmentEvent::class);
    }

    public function appointmentEvent(): BelongsTo
    {
        return $this->belongsTo(AppointmentEvent::class);
    }
}
