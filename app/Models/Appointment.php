<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'appointment_event_id',
        'bot_user_id',
        'appointment_schedule_id',
        'status',
        'name',
        'phone',
        'info',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'bot_user_id' => 'integer',
        'appointment_schedule_id' => 'integer',
        'appointment_event_id' => 'integer',
    ];

    protected $with = ["botUser","schedule","event"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(AppointmentSchedule::class,"appointment_schedule_id","id");
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class,"bot_user_id","id");
    }


    public function event(): HasOne
    {
        return $this->hasOne(AppointmentEvent::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(AppointmentService::class);
    }
}
