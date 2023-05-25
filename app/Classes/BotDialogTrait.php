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
    private function sendDialogData($botDialogCommand): void
    {

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

            $this->replyMediaGroup($media);

            if (!is_null($botDialogCommand->inline_keyboard_id))
                $this->replyInlineKeyboard($msg, $keyboard);
            else
                $this->reply($msg);

        } else if (count($botDialogCommand->images) === 1) {
            $this->replyPhoto("<b>" . $msg,
                InputFile::create(storage_path("app/public") . "/images-by-bot-id/" . $botDialogCommand->bot_id . "/" . $botDialogCommand->images[0]),
                $keyboard
            );

        } else if (count($botDialogCommand->images) === 0) {
            $this->reply($msg);
        }

    }

    public function startBotDialog($dialogCommandId): void
    {

        if (is_null($dialogCommandId))
            return;

        $botDialogCommand = BotDialogCommand::query()
            ->where("id", $dialogCommandId)
            ->first();

        if (is_null($botDialogCommand))
            return;

        $botUser = $this->currentBotUser();

        $botUser->in_dialog_mode = true;
        $botUser->save();

        BotDialogResult::query()->create([
            'bot_user_id' => $botUser->id,
            'bot_dialog_command_id' => $botDialogCommand->id,
            'current_input_data' => null,
            'summary_input_data' => [],
            'completed_at' => null,
        ]);

        $this->sendDialogData($botDialogCommand);
    }

    private function validateInput($text, $pattern = null): bool
    {

        if (is_null($pattern))
            return true;

        $matches = [];

        preg_match($pattern, $text, $matches);

        Log::info(print_r($pattern, true));
        Log::info(print_r($text, true));
        Log::info(print_r($matches, true));

        return count($matches)>0;
    }

    public function nextBotDialog($text): void
    {
        $botUser = $this->currentBotUser();

        if (trim(strtolower($text)) === "/start"
            || trim(strtolower($text)) === "/stop") {
            $this->stopBotDialog();
            $this->reply("Диалог преждевременно завершен!");
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
            $this->reply($botDialogCommand->error_text ?? 'Ошибка ввода');
            return;
        }

        $tmpSummary = $dialog->summary_input_data ?? [];
        $tmpSummary[] = $text;

        $dialog->current_input_data = $text ?? null;
        $dialog->summary_input_data = $tmpSummary;
        $dialog->completed_at = Carbon::now();
        $dialog->save();

        $this->reply($botDialogCommand->post_text ?? 'Данные успешно сохранены');

        if (!is_null($botDialogCommand->next_bot_dialog_command_id)) {
            $nextBotDialogCommand = BotDialogCommand::query()
                ->find($botDialogCommand->next_bot_dialog_command_id);
            $this->sendDialogData($nextBotDialogCommand ?? null);
        } else {
            $botUser->in_dialog_mode = false;
            $botUser->save();

            $tmp = $dialog->summary_input_data ?? [];

            $this->reply(print_r($tmp, true));
        }

    }

    public function currentBotUserInDialog(): bool
    {
        $botUser = $this->currentBotUser();

        return $botUser->in_dialog_mode ?? false;
    }

    public function stopBotDialog(): void
    {
        $botUser = $this->currentBotUser();

        $botUser->in_dialog_mode = false;
        $botUser->save();

        $dialogs = BotDialogResult::query()
            ->with(["botDialogCommand"])
            ->where("bot_user_id", $botUser->id)
            ->whereNull("completed_at")
            ->orderBy("created_at", "ASC")
            ->get();


        foreach ($dialogs as $dialog) {
            $dialog->completed_at = Carbon::now();
            $dialog->save();
        }

        $tmp = $dialogs[count($dialogs) - 1]->summary_input_data ?? [];

        $this->reply(print_r($tmp, true));


    }
}
