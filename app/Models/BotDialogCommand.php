<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'input_pattern',
        'inline_keyboard_id',
        'images',
        'next_bot_dialog_command_id',
        'bot_dialog_group_id',
        'result_channel',

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
        'images' => 'array',
        'next_bot_dialog_command_id' => 'integer',
    ];

    protected $with = ["bot", "inlineKeyboard"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botDialogGroup(): BelongsTo
    {
        return $this->belongsTo(BotDialogGroup::class);
    }

    public function inlineKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class);
    }

    public function nextBotDialogCommand(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class);
    }
}
