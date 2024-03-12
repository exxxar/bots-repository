<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PromoCode extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'code',
        'description',
        'slot_amount',
        'cashback_amount',
        'max_activation_count',
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
        'slot_amount' => 'integer',
        'cashback_amount' => 'double',
        'is_active' => 'boolean',
    ];

    protected $with = ["botUsers","scripts"];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }


    public function botUsers(): BelongsToMany
    {
        return $this->belongsToMany(BotUser::class);
    }

    public function scripts(): BelongsToMany
    {
        return $this->BelongsToMany(BotMenuSlug::class,"manager_profile_has_scripts","manager_profile_id","bot_menu_slug_id");
    }
}
