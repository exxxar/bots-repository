<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ingredient extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'image',
        'weight',
        'price',
        'bot_id',
        'food_constructor_id',
        'sub',
        'ingredient_category_id',
        'is_checked',
        'is_disabled',
        'is_global',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'food_constructor_id' => 'integer',
        'sub' => 'array',
        'ingredient_category_id' => 'integer',
        'is_checked' => 'boolean',
        'is_disabled' => 'boolean',
        'is_global' => 'boolean',
    ];

    public function ingredientCategory(): BelongsTo
    {
        return $this->belongsTo(IngredientCategory::class);
    }

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function foodConstructor(): BelongsTo
    {
        return $this->belongsTo(FoodConstructor::class);
    }


}
