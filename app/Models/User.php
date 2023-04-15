<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'phone',
        'name',
        'password',
        'fio_from_telegram',
        'telegram_chat_id',
        'avatar_url',
        'role_id',
        'blocked_at',
        'blocked_message',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'role_id' => 'integer',
        'blocked_at' => 'timestamp',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }


    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class,'id','user_id');
    }

    public function referralHistory(): BelongsTo
    {
        return $this->belongsTo(ReferralHistory::class);
    }

    public function basket(): BelongsTo
    {
        return $this->belongsTo(Basket::class);
    }


    public function role(): HasOne
    {
        return $this->hasOne(Role::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }


}
