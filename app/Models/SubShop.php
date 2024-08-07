<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubShop extends Model
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
        'keyword',
        'image',
        'schedule',
        'config',
        'is_active',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'schedule' => 'array',
        'config' => 'array',
        'is_active' => 'boolean',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }
}
