<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Partner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'bot_partner_id',
        'title',
        'description',
        'image',
        'is_active',
        'extra_charge',
        'config',
        'legal_info',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'bot_partner_id' => 'integer',
        'is_active' => 'boolean',
        'config' => 'array',
        'legal_info' => 'array',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function botPartner(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'bot_id','bot_partner_id');
    }
}
