<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomField extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value',
        'bot_user_id',
        'bot_custom_field_setting_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_user_id' => 'integer',
        'bot_custom_field_setting_id' => 'integer',
    ];

    protected $with = ["config"];

    public function config(): BelongsTo
    {
        return $this->belongsTo(BotCustomFieldSetting::class,'bot_custom_field_setting_id','id');
    }

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }


}
