<?php

namespace App\Jobs;

use App\Models\Bot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class SendPhotoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BotTrait;

    private $botId;
    private $caption;
    private $photo;
    private $messageThreadId;
    private $chatId;
    private $replyKeyboard;
    private $inlineKeyboard;
    private $keyboardSettings;
    /**
     * Create a new job instance.
     */
    public function __construct(
        $botId,
        $chatId,
        $caption,
        $photo,
        $replyKeyboard = null,
        $inlineKeyboard = null,
        $messageThreadId = null,
        $keyboardSettings = null,
    )
    {
        $this->botId = $botId;
        $this->chatId = $chatId;
        $this->caption = $caption;
        $this->photo = $photo;
        $this->replyKeyboard = $replyKeyboard;
        $this->inlineKeyboard = $inlineKeyboard;
        $this->messageThreadId = $messageThreadId;
        $this->keyboardSettings = $keyboardSettings;

        $this->messageAutFormat();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        try {
            $api = $this->initApi();

            $tmp = [
                "chat_id" => $this->chatId,
                "photo" => $this->photo,
                "caption" => $this->caption,
                "parse_mode" => "HTML"

            ];

            $tmp = $this->attachElements($tmp);

            if (!is_null($api))
                $api->sendPhoto($tmp);


        } catch (\Exception $e) {

        }
    }
}
