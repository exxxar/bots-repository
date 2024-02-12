<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InlineQueryItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'inline_query_slug_id',
        'type',
        'title',
        'description',
        'input_message_content',
        'inline_keyboard_id',
        'custom_settings',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'inline_query_slug_id' => 'integer',
        'input_message_content' => 'array',
        'inline_keyboard_id' => 'integer',
        'custom_settings' => 'array',
    ];

    public function inlineSlug(): BelongsTo
    {
        return $this->belongsTo(InlineQuerySlug::class);
    }

    public function inlineKeyboard(): BelongsTo
    {
        return $this->belongsTo(BotMenuTemplate::class);
    }


}
