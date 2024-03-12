<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ManagerProfile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_user_id',
        'info',
        'referral',
        'image',
        'strengths',
        'weaknesses',
        'educations',
        'social_links',
        'skills',
        'stable_personal_discount',
        'permanent_personal_discount',
        'max_company_slot_count',
        'max_bot_slot_count',
        'balance',
        'verified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_user_id' => 'integer',
        'strengths' => 'array',
        'weaknesses' => 'array',
        'educations' => 'array',
        'social_links' => 'array',
        'skills' => 'array',
        'stable_personal_discount' => 'double',
        'permanent_personal_discount' => 'double',
        'verified_at' => 'timestamp',
    ];

    protected $with = ["scripts"];

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }


    public function scripts(): BelongsToMany
    {
        return $this->BelongsToMany(BotMenuSlug::class,"manager_profile_has_scripts","manager_profile_id","bot_menu_slug_id");
    }

}
