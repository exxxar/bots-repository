<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class BotMenuSlug extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'parent_slug_id',
        'command',
        'comment',
        'slug',
        'config',
        'is_global',
        'deprecated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'config' => 'array',
        'is_global' => 'boolean',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }



    public function page(): HasOne
    {
        return $this->hasOne(BotPage::class,'bot_menu_slug_id','id');
    }

    public function dialog(): HasOne
    {
        return $this->hasOne(BotDialogCommand::class,'next_bot_dialog_command_id','id');
    }

    public function menuSlug(): HasOne
    {
        return $this->hasOne(Bot::class,'next_bot_menu_slug_id','id');
    }




}
