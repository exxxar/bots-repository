<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Basket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_collection_id',
        'count',
        'params',
        'bot_user_id',
        'bot_id',
        'table_id',
        'table_approved_at',
        'ordered_at',
        'comment',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'product_id' => 'integer',
        'product_collection_id' => 'integer',
        'bot_user_id' => 'integer',
        'bot_id' => 'integer',
        'params' => 'array',
        'table_id' => 'integer',
        'table_approved_at' => 'timestamp',
        'ordered_at' => 'timestamp',
    ];

    protected $with = ["product", "collection"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(ProductCollection::class, "product_collection_id", "id");
    }

}
