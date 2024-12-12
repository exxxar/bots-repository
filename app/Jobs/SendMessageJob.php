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

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BotTrait;

    private $botId;
    private $message;
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
        $message,
        $replyKeyboard = null,
        $inlineKeyboard = null,
        $messageThreadId = null,
        $keyboardSettings = null,
    )
    {
        $this->botId = $botId;
        $this->chatId = $chatId;
        $this->message = $message;
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
                "text" => $this->message,
                "parse_mode" => "HTML"
            ];

            if (is_null($api))
                return;

            $tmp = $this->attachElements($tmp);

            $api->sendMessage($tmp);
        } catch (\Exception $e) {

        }
    }
}
