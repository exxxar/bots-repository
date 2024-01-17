<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'image',
        'description',
        'completed_at',
        "try_count",
        "polling_mode",
        "try_count",
        "is_active",
        "success_percent",
        "success_message",
        "failure_message",
        'start_at',
        'end_at',
        'display_type',
        'time_limit',
        'show_answers',
        'bot_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'completed_at' => 'datetime',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'time_limit' => 'double',
        'show_answers' => 'boolean',
        'bot_id' => 'integer',
    ];

    protected $with = ["questions","commands"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(QuizQuestion::class);
    }


    public function commands(): BelongsToMany
    {
        return $this->belongsToMany(QuizCommand::class);
    }
}
