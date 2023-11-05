<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Models\BotDialogCommand;
use App\Models\BotDialogResult;
use App\Models\BotMenuTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

trait BotDialogTrait
{
    private function sendDialogData($botDialogCommand, $channel = null): void
    {

        $channel = $this->getCurrentChatId() ?? $channel;

        if (is_null($botDialogCommand))
            return;

        $msg = $botDialogCommand->pre_text ?? 'Введите данные';
        $menuTemplate = BotMenuTemplate::query()->find($botDialogCommand->inline_keyboard_id);
        $keyboard = $menuTemplate->menu ?? [];

        if (count($botDialogCommand->images) > 1) {

            $media = [];
            foreach ($botDialogCommand->images as $image) {
                $media[] = [
                    "media" => env("APP_URL") . "/images-by-bot-id/" . $botDialogCommand->bot_id . "/" . $image,
                    "type" => "photo",
                    "caption" => env("APP_URL") . "/images-by-bot-id/" . $botDialogCommand->bot_id . "/" . $image
                ];
            }

            $this->sendMediaGroup($channel, $media);

            if (!is_null($botDialogCommand->inline_keyboard_id))
                $this->sendInlineKeyboard($channel, $msg, $keyboard);
            else
                $this->sendMessage($channel, $msg);

        } else if (count($botDialogCommand->images) === 1) {
            $this->sendPhoto($channel, "<b>" . $msg,
                InputFile::create(storage_path("app/public") . "/images-by-bot-id/" . $botDialogCommand->bot_id . "/" . $botDialogCommand->images[0]),
                $keyboard
            );

        } else if (count($botDialogCommand->images) === 0) {
            $this->sendMessage($channel, $msg);
        }

    }

    public function startBotDialog($dialogCommandId, $botUser = null): void
    {

        if (is_null($dialogCommandId))
            return;

        $botDialogCommand = BotDialogCommand::query()
            ->where("id", $dialogCommandId)
            ->first();

        if (is_null($botDialogCommand))
            return;

        $botUser = is_null($botUser) ? $this->currentBotUser() : $botUser;

        if (is_null($botUser))
            return;

        $botUser->in_dialog_mode = true;
        $botUser->save();

        BotDialogResult::query()->create([
            'bot_user_id' => $botUser->id,
            'bot_dialog_command_id' => $botDialogCommand->id,
            'current_input_data' => null,
            'summary_input_data' => [],
            'completed_at' => null,
        ]);

        $this->sendDialogData($botDialogCommand, $botUser->telegram_chat_id ?? null);
    }

    private function validateInput($text, $pattern = null): bool
    {

        if (is_null($pattern))
            return true;

        $matches = [];

        try {
            preg_match_all($pattern, $text, $matches);
        } catch (\Exception $e) {

        }


        return count($matches) > 0;
    }

    public function nextBotDialog($text, $botUser = null): void
    {
        $botUser = is_null($botUser) ? $this->currentBotUser() : $botUser;

        // $botUser = $this->currentBotUser();

        if (trim(strtolower($text)) === "/start"
            || trim(strtolower($text)) === "/stop") {
            $this->stopBotDialog($botUser);
            $this->sendMessage($botUser->telegram_chat_id ?? null,
                "Диалог преждевременно завершен!");
        }

        $dialog = BotDialogResult::query()
            ->with(["botDialogCommand"])
            ->where("bot_user_id", $botUser->id)
            ->whereNull("completed_at")
            ->orderBy("created_at", "DESC")
            ->first();

        if (is_null($dialog))
            return;

        $botDialogCommand = $dialog->botDialogCommand;
        if (!$this->validateInput($text, $botDialogCommand->input_pattern ?? null)) {
            $this->sendMessage($botUser->telegram_chat_id ?? null,
                $botDialogCommand->error_text ?? 'Ошибка ввода');
            return;
        }

        $tmpSummary = $dialog->summary_input_data ?? [];
        $tmpSummary[] = $text;

        $dialog->current_input_data = $text ?? null;
        $dialog->summary_input_data = $tmpSummary;
        $dialog->completed_at = Carbon::now();
        $dialog->save();

        $this->sendMessage($botUser->telegram_chat_id ?? null,
            $botDialogCommand->post_text ?? 'Данные успешно сохранены');

        if (!is_null($botDialogCommand) &&
            !is_null($botDialogCommand->next_bot_dialog_command_id ?? null)) {
            $nextBotDialogCommand = BotDialogCommand::query()
                ->find($botDialogCommand->next_bot_dialog_command_id);

            BotDialogResult::query()->create([
                'bot_user_id' => $botUser->id,
                'bot_dialog_command_id' => $nextBotDialogCommand->id,
                'current_input_data' => null,
                'summary_input_data' => $dialog->summary_input_data ?? [],
                'completed_at' => null,
            ]);

            $this->sendDialogData($nextBotDialogCommand ?? null,
                $botUser->telegram_chat_id ?? null,);

        } else {
            $botUser->in_dialog_mode = false;
            $botUser->save();

            $tmp = $dialog->summary_input_data ?? [];

            $this->dialogResponse($botUser, $botDialogCommand, $tmp);
        }

    }

    public function currentBotUserInDialog($botUser = null): bool
    {
        $botUser = is_null($botUser) ? $this->currentBotUser() : $botUser;

        return $botUser->in_dialog_mode ?? false;
    }

    public function stopBotDialog($botUser = null): void
    {
        $botUser = is_null($botUser) ? $this->currentBotUser() : $botUser;

        $botUser->in_dialog_mode = false;
        $botUser->save();

        $dialogs = BotDialogResult::query()
            ->with(["botDialogCommand"])
            ->where("bot_user_id", $botUser->id)
            ->whereNull("completed_at")
            ->orderBy("created_at", "ASC")
            ->get();


        if (count($dialogs) == 0)
            return;

        foreach ($dialogs as $dialog) {
            $dialog->completed_at = Carbon::now();
            $dialog->save();
        }

        $botDialogCommand = $dialogs[count($dialogs) - 1]->botDialogCommand;

        $tmp = $dialog->summary_input_data ?? [];

        $this->dialogResponse($botUser, $botDialogCommand, $tmp);


    }

    private function dialogResponse($botUser, $botDialogCommand, $dialogData = []): void
    {
        /*     if (!is_null($botDialogCommand->result_channel)) */
        $tmpMessage = "Диалог с пользователем <b>#$botUser->id</b> [<b>#$botDialogCommand->id</b>]: \n";

        $step = 1;
        foreach ($dialogData as $data) {
            $tmpMessage .= "Шаг $step: $data \n";

            $step++;
        }

        $channel = $botDialogCommand->result_channel ??
            $this->bot->order_channel ??
            $this->bot->main_channel ?? null;

        $tmpMessage .= "Пользователь:\n"
            . "-ТГ id: " . ($botUser->telegram_chat_id ?? '-') . "\n"
            . "-имя из ТГ: " . ($botUser->fio_from_telegram ?? 'Имя из телеграм не указано') . "\n"
            . "-введенное имя: " . ($botUser->name ?? 'Введенное имя не указано') . "\n"
            . "-телефон: " . ($botUser->phone ?? 'Номер телефона не указан') . "\n"
            . "-email: " . ($botUser->email ?? 'Почта не указана') . "\n";

        $thread = $this->bot->topics["questions"] ?? null;

        Log::info($tmpMessage);
        Log::info("channel $channel thread " . ($thread ?? '-'));

        $this->sendMessage($channel, $tmpMessage, $thread);

    }
}
