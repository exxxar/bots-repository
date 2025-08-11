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
use Illuminate\Support\Facades\File;
use Telegram\Bot\FileUpload\InputFile;

class CheckBotLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:check-logs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'проверка пула сообщений к админам';

    protected function processAndDeleteLogFiles(string $directory, callable $callback): void
    {
        // Абсолютный путь к директории (например storage_path('logs'))
        $path = storage_path($directory);

        if (!File::isDirectory($path)) {
            throw new \InvalidArgumentException("Папка {$path} не найдена.");
        }

        // Получаем все файлы в директории
        $files = File::files($path);

        foreach ($files as $file) {
            // $file - объект SplFileInfo
            $filePath = $file->getPathname();

            // Выполняем действие из callback, передаём путь файла
            $callback($filePath, $file->getFilename());

            // Удаляем файл
            File::delete($filePath);
        }
    }


    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);

        $bot = Bot::query()
            ->where("bot_domain", env('LOGGER_BOT_DOMAIN'))
            ->first();

        if (is_null($bot))
            return;

        $this->processAndDeleteLogFiles('logs', function ($filePath, $name) use ($bot) {
            $content = File::get($filePath);

            $name = explode('.', $name)[0] ?? $name;

            $callbackChannel = env("LOGGER_BOT_CHANNEL");

            switch ($name) {
                case 'error':
                case 'critical':
                    $icon = "🔴";
                    $thread = env('LOGGER_BOT_CHANNEL_CRITICAL_THREAD');
                    break;
                case 'warning':
                    $icon = "🟠";
                    $thread = env('LOGGER_BOT_CHANNEL_WARNING_THREAD');
                    break;
                default:
                case 'info':
                    $icon = "🟢";
                    $thread = env('LOGGER_BOT_CHANNEL_INFO_THREAD');
                    break;
            }
            sleep(1);
            BotMethods::bot()
                ->whereBot($bot)
                ->sendDocument(
                    $callbackChannel,
                    "$icon Лог событий системы за " . (Carbon::now("+3")->format('Y-m-d H:i:s')),
                    InputFile::createFromContents($content, "$name.txt"),
                    $thread
                );
        });

        ini_set('max_execution_time', 300);
    }
}
