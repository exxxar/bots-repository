<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BotDialogAnswer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'bot_dialog_command_id',
        'answer',
        'pattern',
        'next_bot_dialog_command_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'bot_dialog_command_id' => 'integer',
        'next_bot_dialog_command_id' => 'integer',
    ];

    public function ownDialog(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class, "bot_dialog_command_id", "id");
    }

    public function nextDialog(): BelongsTo
    {
        return $this->belongsTo(BotDialogCommand::class, "next_bot_dialog_command_id", "id");
    }
}
