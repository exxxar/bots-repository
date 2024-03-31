<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentEvent extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'title',
        'subtitle',
        'description',
        'images',
        'is_group',
        'max_people',
        'min_people',
        'on_start_appointment',
        'on_cancel_appointment',
        'on_after_appointment',
        'on_repeat_appointment',
        'address',
        'coords',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'images' => 'array',
        'is_group' => 'boolean',
    ];

    protected $with = ["services", "times"];

    protected $appends = ["min_price"];

    public function getMinPriceAttribute()
    {
        return $this->services()->min("price") ?? 0;
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(AppointmentService::class);
    }

    public function times(): HasMany
    {
        return $this->hasMany(AppointmentSchedule::class);
    }

}
