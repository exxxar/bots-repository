<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'address',
        'phones',
        'links',
        'email',
        'schedule',
        'manager',
        'vat_code',
        'is_active',
        'creator_id',
        'owner_id',
        'blocked_message',
        'law_params',
        'blocked_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'phones' => 'array',
        'links' => 'array',
        'schedule' => 'array',
        'law_params' => 'array',
        'is_active' => 'boolean',
        'blocked_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = ["bot_count"];

    public function getBotCountAttribute()
    {
        return $this->bots()->count() ?? 0;

    }

    public function bots(): HasMany
    {
        return $this->hasMany(Bot::class);
    }

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
