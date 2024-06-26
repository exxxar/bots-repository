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
        "polling_mode",
        "round_mode",
        "try_count",
        "is_active",
        "success_percent",
        "success_message",
        "success_inline_keyboard_id",
        "failure_inline_keyboard_id",
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
        'is_active' => 'boolean',
        'polling_mode' => 'boolean',
        'round_mode' => 'boolean',
        'bot_id' => 'integer',
        "success_message"=> 'array',
        "failure_message"=> 'array',
    ];

    protected $with = ["questions","commands","successReplyKeyboard","failureReplyKeyboard"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function successReplyKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,'success_inline_keyboard_id','id');
    }

    public function failureReplyKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,'failure_inline_keyboard_id','id');
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
