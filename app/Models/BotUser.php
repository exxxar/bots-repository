<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;

class BotUser extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_id',
        'user_id',
        'telegram_chat_id',
        'parent_id',
        'is_vip',
        'is_admin',
        'is_work',
        'is_manager',
        'is_deliveryman',
        'in_dialog_mode',

        'user_in_location',
        'location_comment',
        'current_latitude',
        'current_longitude',

        'name',
        'username',
        'phone',
        'email',
        'birthday',
        'age',
        'city',
        'country',
        'address',
        'sex',

        'fio_from_telegram',

        'blocked_at',
        'blocked_message',

        'config'

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_id' => 'integer',
        'user_id' => 'integer',
        'parent_id' => 'integer',
        'is_admin' => 'boolean',
        'is_work' => 'boolean',
        'is_vip' => 'boolean',
        'is_manager' => 'boolean',
        'is_deliveryman' => 'boolean',
        'in_dialog_mode' => 'boolean',
        'sex' => 'boolean',
        'user_in_location' => 'boolean',
        'config' => 'array',
    ];

    protected $with = ["cashBack","manager","fields"];

    public function fields(): HasMany
    {
        return $this->hasMany(CustomField::class);
    }


    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cashBack(): belongsTo
    {

        return $this->belongsTo(CashBack::class,"id", "bot_user_id");
    }

    public function parent(): HasOne
    {
        return $this->hasOne(BotUser::class,'id','parent_id');
    }

    public function manager(): belongsTo
    {
        return $this->belongsTo(ManagerProfile::class,'id','bot_user_id');
    }
}
