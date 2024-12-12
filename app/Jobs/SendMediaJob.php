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

class SendMediaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BotTrait;

    private $botId;
    private $media;
    private $messageThreadId;
    private $chatId;

    /**
     * Create a new job instance.
     */
    public function __construct(
        $botId,
        $chatId,
        $media,
        $messageThreadId = null,
    )
    {
        $this->botId = $botId;
        $this->chatId = $chatId;
        $this->media = $media;
        $this->messageThreadId = $messageThreadId;

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
                "media" => $this->media,
                "parse_mode" => "HTML"

            ];

            $tmp = $this->attachElements($tmp);

            if (!is_null($api))
                $api->sendMediaGroup($tmp);


        } catch (\Exception $e) {

        }
    }
}
