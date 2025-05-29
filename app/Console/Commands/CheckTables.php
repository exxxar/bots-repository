<?php

namespace App\Console\Commands;

use App\Classes\BotMethods;
use App\Facades\BotManager;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Story;
use App\Models\Table;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bot:check-tables';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ежедневная проверка истечения времени столика';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ini_set('max_execution_time', 30000);

        $tables = Table::query()
            ->whereNull("closed_at")
            ->get();

        foreach ($tables as $table) {
            $table->closed_at = Carbon::now();
            $table->save();
        }

        ini_set('max_execution_time', 300);
    }
}
