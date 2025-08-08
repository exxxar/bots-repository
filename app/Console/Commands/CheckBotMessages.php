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

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);

        $folder = 'chat-logs'; // Папка внутри storage/app
        $files = Storage::files($folder);

        foreach ($files as $file) {
            try {
                $content = Storage::get($file);
                $data = json_decode($content, true, 512, JSON_THROW_ON_ERROR);

                $bot = Bot::query()
                    ->find($data["bot_id"]??null);

                if (is_null($bot))
                    continue;

                $name = $data['user']["name"] ?? 'Пользователь';

                $message = "#ответ от $name\n";

                foreach ($data['messages']  ?? [] as $m)
                    $message .= "[".$m["timestamp"]."]:".$m["message"]."\n";

                $telegramChatId = $data['user']["telegram_chat_id"] ?? null;

                $message .=  "\n<a href='tg://user?id=$telegramChatId'>Перейти к чату с пользователем</a>\n";

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

            } catch (\JsonException $e) {
                Log::warning("Удаление невалидного файла: $file");
            }

            // Удаляем файл в любом случае
            Storage::delete($file);
        }


        ini_set('max_execution_time', 300);
    }
}
