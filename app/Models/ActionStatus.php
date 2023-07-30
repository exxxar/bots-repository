<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ActionStatus extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'bot_id',
        'slug_id',
        'max_attempts',
        'current_attempts',
        'completed_at',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'bot_id' => 'integer',
        'slug_id' => 'integer',
        'completed_at' => 'datetime:Y-m-d',
        'data' => 'array',
    ];

    protected $appends = ["bot_user"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function slug(): BelongsTo
    {
        return $this->belongsTo(BotMenuSlug::class);
    }

    public function getBotUserAttribute()
    {
        return BotUser::query()
            ->where("user_id", $this->user_id)
            ->where("bot_id", $this->bot_id)
            ->first();

    }


}
