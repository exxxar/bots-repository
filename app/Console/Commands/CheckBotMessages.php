<?php

namespace App\Console\Commands;


use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CheckBotMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:check-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'проверка пула сообщений к админам';

    public function processTelegramImages()
    {
        $folder = 'telegram-images'; // Папка, где хранятся config-*.json

        if (!Storage::exists($folder)) {
            return;
        }

        $files = Storage::files($folder);

        foreach ($files as $filePath) {



            if (!str_contains($filePath, 'images-') || pathinfo($filePath, PATHINFO_EXTENSION) !== 'json') {
                // Не конфиг — удаляем
                Storage::delete($filePath);
                continue;
            }


            try {
                $content = Storage::get($filePath);

                $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);


                // Проверяем структуру
                if (
                    !isset($data['bot_id']) ||
                    !isset($data['user']) ||
                    !isset($data['images']) ||
                    !is_array($data['images'])
                ) {
                    Storage::delete($filePath);
                    continue;
                }

                $bot = Bot::query()
                    ->find($data["bot_id"] ?? null);

                if (is_null($bot))
                    continue;

                $chatId = $data["user"]['telegram_chat_id'];
                $fileIds = $data['images'];

                $channel = $data["channel"] ?? null;
                $thread = $data["thread"] ?? null;

                $message = "Подпись к фотографиям:\n";

                foreach ($data["messages"] ?? [] as $m)
                    $message .= $m."\n";

                $message .= "\n<a href='tg://user?id=$chatId'>Перейти к чату с пользователем</a>\n";

                $link = $data["link"] ?? null;


                if (count($fileIds) > 1) {
                    $media = [];
                    foreach ($fileIds as $fileId) {
                        $media[] = [
                            "media" => $fileId,
                            "type" => "photo",
                            "caption" => ""
                        ];
                    }

                    BotMethods::bot()
                        ->whereBot($bot)
                        ->sendMediaGroup($channel, json_encode($media), $thread);

                    sleep(1);

                    BotMethods::bot()
                        ->whereBot($bot)
                        ->sendInlineKeyboard($channel, $message,
                            [
                                [
                                    ["text" => "Ответить через бота", "url" => $link]
                                ]
                            ],
                            $thread);

                }

                if (count($fileIds) == 1)
                    BotMethods::bot()
                        ->whereBot($bot)
                        ->sendPhoto($channel, $message, $fileIds[0],
                            [
                                [
                                    ["text" => "Ответить через бота", "url" => $link]
                                ]
                            ], $thread);

                sleep(1);
                BotMethods::bot()
                    ->whereBot($bot)
                    ->sendMessage(
                        $chatId,
                        "Ваши изображения успешно доставлены администратору!"
                    );


            } catch (\Throwable $e) {
                Log::info($e->getMessage());
            }
            Storage::delete($filePath);
        }

    }

    public function processTelegramMessages()
    {
        $folder = 'chat-logs'; // Папка внутри storage/app
        $files = Storage::files($folder);

        foreach ($files as $file) {
            try {
                $content = Storage::get($file);
                $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                $bot = Bot::query()
                    ->find($data["bot_id"] ?? null);

                if (is_null($bot))
                    continue;

                $name = $data['user']["name"] ?? 'Пользователь';
                $timestamp = Carbon::parse($data['timestamp'] ?? Carbon::now())->timestamp;

                $current_time = Carbon::now()->timestamp;
                if ($current_time - $timestamp < 300)
                    continue;

                $message = "#ответ от $name:\n";

                foreach ($data['messages'] ?? [] as $m)
                    $message .= "[" . Carbon::parse($m["timestamp"])->format("H:i:s") . "]: " . $m["message"] . "\n";

                $telegramChatId = $data['user']["telegram_chat_id"] ?? null;

                $message .= "\n<a href='tg://user?id=$telegramChatId'>Перейти к чату с пользователем</a>\n";

                $link = $data["link"] ?? null;

                BotMethods::bot()
                    ->whereBot($bot)
                    ->sendInlineKeyboard(
                        $data['channel'],
                        $message,
                        [
                            [
                                ["text" => "Ответить через бота", "url" => $link]
                            ]
                        ],
                        $data['thread'] ?? null);

                sleep(1);
                BotMethods::bot()
                    ->whereBot($bot)
                    ->sendMessage(
                        $telegramChatId,
                        "Ваше сообщение успешно доставлено администратору!"
                    );

            } catch (\JsonException $e) {
                Log::warning("Удаление невалидного файла: $file");
            }

            // Удаляем файл в любом случае
            Storage::delete($file);
        }

    }

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);

        $this->processTelegramMessages();

        $this->processTelegramImages();

        ini_set('max_execution_time', 300);
    }
}
