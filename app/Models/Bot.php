<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'welcome_message',
        'bot_domain',
        'bot_token',
        'bot_token_dev',
        'order_channel',
        'main_channel',
        'vk_shop_link',
        'callback_link',
        'balance',
        'tax_per_day',
        'image',
        'description',
        'info_link',
        'social_links',
        'is_active',
        'auto_cashback_on_payments',
        'maintenance_message',
        'bot_type_id',
        'level_1',
        'level_2',
        'level_3',
        'blocked_message',
        'payment_provider_token',
        'blocked_at',
        'is_template',
        'template_description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'balance' => 'double',
        'tax_per_day' => 'double',
        'social_links' => 'array',
        'is_active' => 'boolean',
        'auto_cashback_on_payments' => 'boolean',
        'bot_type_id' => 'integer',
        'level_1' => 'double',
        'level_2' => 'double',
        'level_3' => 'double',
        'blocked_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'deleted_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $with = ["company","amo"];

    public function imageMenus(): HasMany
    {
        return $this->hasMany(ImageMenu::class);
    }

    public function notificationSchedules(): HasMany
    {
        return $this->hasMany(NotificationSchedule::class);
    }

    public function cashBackHistories(): HasMany
    {
        return $this->hasMany(CashBackHistory::class);
    }

    public function referralHistories(): HasMany
    {
        return $this->hasMany(ReferralHistory::class);
    }

    public function botUsers(): HasMany
    {
        return $this->hasMany(BotUser::class);
    }

    public function cashBacks(): HasMany
    {
        return $this->hasMany(CashBack::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Events::class);
    }

    public function baskets(): HasMany
    {
        return $this->hasMany(Basket::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function amo(): HasOne
    {
        return $this->hasOne(AmoCrm::class);
    }

    public function botType(): BelongsTo
    {
        return $this->belongsTo(BotType::class);
    }

}
