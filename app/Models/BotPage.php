<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BotPage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_menu_slug_id',
        'content',
        'images',
        'video',
        'reply_keyboard_title',
        'reply_keyboard_id',
        'inline_keyboard_id',
        'bot_id',
        'next_page_id',
        'next_bot_dialog_command_id',
        'next_bot_menu_slug_id',
        'is_external',
        'rules_if',
        'rules_else_page_id',
        'rules_if_message',
        'rules_else_message',

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_menu_slug_id' => 'integer',
        'images' => 'array',
        'rules_if' => 'array',
        'rules_else_page_id' => 'integer',
        'reply_keyboard_id' => 'integer',
        'inline_keyboard_id' => 'integer',
        'bot_id' => 'integer',
        'is_external' => 'boolean',
        'next_page_id' => 'integer',
        'next_bot_dialog_command_id'=> 'integer',
        'next_bot_menu_slug_id'=> 'integer',
    ];

    protected $with = ['slug','replyKeyboard','inlineKeyboard'];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function slug(): BelongsTo
    {
        return $this->belongsTo(BotMenuSlug::class,'bot_menu_slug_id','id');
    }

    public function replyKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,'reply_keyboard_id','id');
    }

    public function inlineKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class,'inline_keyboard_id','id');
    }

    public function nextPage(): BelongsTo
    {
        return $this->belongsTo(BotPage::class,'next_page_id','id');
    }

    public function nextDialogCommand(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class,'next_bot_dialog_command_id','id');
    }

    public function nextMenuSlug(): BelongsTo
    {
        return $this->belongsTo(BotMenuSlug::class,'next_bot_menu_slug_id','id');
    }

}
