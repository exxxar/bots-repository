<?php

namespace App\Console\Commands;

use App\Classes\BotMethods;
use App\Facades\BotManager;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Story;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckStories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:check-stories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ежедневная проверка истечения историй';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);

        $stories = Story::query()
            ->get();

        foreach ($stories as $story) {

            $createdAt = Carbon::parse($story->created_at);
            $now = Carbon::now();

            if ($createdAt->lessThan($now) && $createdAt->diffInSeconds($now) > 2592000) {
               $story->delete();
            }
        }

        ini_set('max_execution_time', 300);
    }
}
