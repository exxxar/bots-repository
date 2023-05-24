<?php

namespace Database\Seeders;

use App\Models\BotDialogCommand;
use Illuminate\Database\Seeder;

class BotDialogCommandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BotDialogCommand::query()->create([
            'slug'=>"command_test_start_1",
            'pre_text'=>"Добро пожаловать в нашего бота!\nВведите своё Ф.И.О.",
            'post_text'=>"Отлично! Вы ввели %s!",
            'error_text'=>"Ошибочный ввод. Введите в правильном формате.",
            'bot_id'=>1,
            'input_pattern'=>null,
            'inline_keyboard_id'=>null,
            'images'=>null,
            'next_bot_dialog_command_id'=>null,
            'result_channel'=>null
        ]);
    }
}
