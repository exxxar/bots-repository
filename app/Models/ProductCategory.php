<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
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

    protected $appends = ["count"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }


    public function getCountAttribute(){
        return $this->products()->count();
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }


}
