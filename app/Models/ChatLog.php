<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'text',
        'media_content',
        'content_type',
        'bot_id',
        'form_bot_user_id',
        'to_bot_user_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'form_bot_user_id' => 'integer',
        'to_bot_user_id' => 'integer',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function formBotUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function toBotUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }
}
