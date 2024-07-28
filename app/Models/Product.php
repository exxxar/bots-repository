<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'article',
        'vk_product_id',
        'frontpad_article',
        'iiko_article',
        'title',
        'description',
        'images',
        'type',
        'rating',
        'old_price',
        'current_price',
        'variants',
        'in_stop_list_at',
        'bot_id',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'images' => 'array',
        'old_price' => 'double',
        'current_price' => 'double',
        'variants' => 'array',
        'rating' => 'double',
        'in_stop_list_at' => 'datetime:Y-m-d H:i:s',
        'bot_id' => 'integer',
    ];

    protected $with = ["productOptions"];


    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }


    public function productCategories(): BelongsToMany
    {
        return $this->belongsToMany(ProductCategory::class);
    }

    public function productOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }






}
