<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotDialogResult extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_user_id',
        'bot_dialog_command_id',
        'current_input_data',
        'summary_input_data',
        'completed_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_user_id' => 'integer',
        'bot_dialog_command_id' => 'integer',
        'current_input_data' => 'array',
        'summary_input_data' => 'array',
        'completed_at' => 'timestamp',
    ];

    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    public function botDialogCommand(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class);
    }

}
