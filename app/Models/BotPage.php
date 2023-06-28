<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'reply_keyboard_id',
        'inline_keyboard_id',
        'bot_id',
        'next_page_id',
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
        'reply_keyboard_id' => 'integer',
        'inline_keyboard_id' => 'integer',
        'bot_id' => 'integer',
        'next_page_id' => 'integer',
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

}
