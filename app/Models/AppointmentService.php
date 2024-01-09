<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentService extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_event_id',
        'title',
        'description',
        'category',
        'images',
        'price',
        'discount_price',
        'need_prepayment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'appointment_event_id' => 'integer',
        'images' => 'array',
        'price' => 'double',
        'discount_price' => 'double',
        'need_prepayment' => 'boolean',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function appointmentEvents(): BelongsToMany
    {
        return $this->belongsToMany(AppointmentEvent::class);
    }

    public function appointmentEvent(): BelongsTo
    {
        return $this->belongsTo(AppointmentEvent::class);
    }
}
