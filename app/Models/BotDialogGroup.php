<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BotDialogGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'title',
        'bot_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
    ];

    protected $with = ["botDialogCommands"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botDialogCommands(): HasMany
    {
        return $this->hasMany(BotDialogCommand::class)
            ->orderBy('created_at','DESC');
    }
}
