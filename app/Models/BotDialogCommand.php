<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotDialogCommand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'pre_text',
        'post_text',
        'error_text',
        'bot_id',
        'is_empty',
        'input_pattern',
        'inline_keyboard_id',
        'reply_keyboard_id',
        'images',
        'next_bot_dialog_command_id',
        'bot_dialog_group_id',
        'result_channel',
        'result_flags',
        'use_result_as',
        'store_to',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'inline_keyboard_id' => 'integer',
        'reply_keyboard_id' => 'integer',
        'images' => 'array',
        'result_flags' => 'array',
        'is_empty' => 'boolean',
        'next_bot_dialog_command_id' => 'integer',
    ];

    protected $with = ["bot", "inlineKeyboard","replyKeyboard","answers"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(BotDialogAnswer::class,"bot_dialog_command_id","id");
    }

    public function botDialogGroup(): BelongsTo
    {
        return $this->belongsTo(BotDialogGroup::class);
    }

    public function inlineKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,"inline_keyboard_id","id");
    }

    public function replyKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,"reply_keyboard_id","id");
    }

    public function nextBotDialogCommand(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class);
    }


}
