<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BotProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'images',
        'base_price',
        'discount_price',
        'weight',
        'count',
        'in_stock',
        'specifications',
        'variants',
        'owner_id',
        'bot_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'images' => 'array',
        'base_price' => 'double',
        'discount_price' => 'double',
        'weight' => 'double',
        'in_stock' => 'boolean',
        'specifications' => 'array',
        'variants' => 'array',
        'owner_id' => 'integer',
        'bot_id' => 'integer',
    ];

    public function botProductCategories(): BelongsToMany
    {
        return $this->belongsToMany(BotProductCategory::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
