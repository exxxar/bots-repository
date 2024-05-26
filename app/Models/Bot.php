<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

class Bot extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'short_description',
        'long_description',
        'company_id',
        'creator_id',
        'cashback_fire_percent',
        'max_cashback_use_percent',
        'cashback_fire_period',
        'welcome_message',
        'bot_domain',
        'bot_token',
        'bot_token_dev',
        'order_channel',
        'main_channel',
        'commands',
        'message_threads',
        'cashback_config',
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
        'creator_id' => 'integer',
        'cashback_fire_percent' => 'integer',
        'cashback_fire_period' => 'integer',

        'balance' => 'double',
        'tax_per_day' => 'double',
        'social_links' => 'array',
        'message_threads' => 'array',
        'cashback_config' => 'array',
        'commands' => 'array',
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

    protected $with = ["company", "amo", "warnings", "fieldSettings",'YClients','frontPad'];
    protected $appends = ['topics'];

    public function getTopicsAttribute()
    {
        if (is_null($this->message_threads))
            return null;
        $tmp = [];
        $messages = Collection::make($this->message_threads ?? [])
            ->all();

        foreach ($messages as $message) {
            $key = $message["key"] ?? $message->key ?? null;
            $value = $message["value"] ?? $message->key ?? null;
            $tmp[$key] = $value;
        }

        return $tmp;

    }

    public function imageMenus(): HasMany
    {
        return $this->hasMany(ImageMenu::class);
    }

    public function fieldSettings(): HasMany
    {
        return $this->hasMany(BotCustomFieldSetting::class);
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

    public function frontPad(): HasOne
    {
        return $this->hasOne(FrontPad::class);
    }

    public function YClients(): HasOne
    {
        return $this->hasOne(YClients::class);
    }


    public function botType(): BelongsTo
    {
        return $this->belongsTo(BotType::class);
    }

    public function warnings(): hasMany
    {
        return $this->hasMany(BotWarning::class);
    }


}
