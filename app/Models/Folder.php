<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Folder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'title',
        'type',
        'description',
        'is_active',
        'config',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'is_active' => 'boolean',
        'config' => 'array',
    ];

    protected $with = ["pages"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function pages(): HasMany
    {
        return $this->hasMany(BotPage::class);
    }
}
