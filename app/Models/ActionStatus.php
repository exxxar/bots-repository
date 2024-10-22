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
        'bot_user_id',
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

        'user_id' => 'integer',
        'bot_user_id' => 'integer',
        'bot_id' => 'integer',
        'slug_id' => 'integer',
        'completed_at' => 'datetime:Y-m-d',
        'data' => 'array',
    ];


    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
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

    public static function prepare($botUser, $bot, $slug, $maxAttempts = 1){


        $action = ActionStatus::query()
            ->where("user_id", $botUser->user_id)
            ->where("bot_id", $bot->id)
            ->where("slug_id", $slug->id)
            ->first();

        if (is_null($action))
            $action = ActionStatus::query()
                ->create([
                    'user_id' => $botUser->user_id,
                    'bot_id' => $bot->id,
                    'slug_id' => $slug->id,
                    'max_attempts' => $maxAttempts,
                    'current_attempts' => 0,
                    'bot_user_id' => $botUser->id
                ]);

        $action->max_attempts = $maxAttempts;

        if (is_null($action->data)) {
            $action->current_attempts = 0;
            $action->save();
        }

        return $action;
    }

}
