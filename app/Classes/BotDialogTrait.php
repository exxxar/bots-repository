<?php

namespace App\Classes;

use App\Facades\BotManager;
use App\Models\BotDialogCommand;
use App\Models\BotDialogResult;
use App\Models\BotMenuTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\FileUpload\InputFile;

trait BotDialogTrait
{
    private function getVariables($botUser)
    {
        $dialog = BotDialogResult::query()
            ->with(["botDialogCommand"])
            ->where("bot_user_id", $botUser->id)
            ->orderBy("created_at", "DESC")
            ->first();

        if (is_null($dialog))
            return null;

        $variables = $dialog->variables ?? [];

        if (count($variables) == 0)
            return null;

        return $variables;
    }

    private function prepareDataWithVariables($content, $botUser = null)
    {
        $dialog = BotDialogResult::query()
            ->with(["botDialogCommand"])
            ->where("bot_user_id", $botUser->id)
            // ->whereNull("completed_at")
            ->orderBy("created_at", "DESC")
            ->first();


        if (is_null($dialog))
            return $content;

        $variables = $dialog->variables ?? [];

        if (count($variables) == 0)
            return $content;

        foreach ($variables as $variable) {
            $variable = (object)$variable;

            $content = str_replace($variable->key, $variable->value ?? 'не указано', $content);
        }


        return $content;
    }

    private function sendDialogData($botDialogCommand, $botUser = null): void
    {
        if (is_null($botDialogCommand))
            return;


        $msg = $botDialogCommand->pre_text ?? 'Введите данные';

        $msg = $this->prepareDataWithVariables($msg, $botUser);

        $inlineMenuTemplate = BotMenuTemplate::query()->find($botDialogCommand->inline_keyboard_id);
        $inlineKeyboard = $inlineMenuTemplate->menu ?? [];

        $replyMenuTemplate = BotMenuTemplate::query()->find($botDialogCommand->reply_keyboard_id);
        $replyKeyboard = $replyMenuTemplate->menu ?? [];


        $isSent = false;

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


            if (!is_null($botDialogCommand->inline_keyboard_id)) {
                $this->replyInlineKeyboard($msg, $inlineKeyboard);
                $isSent = true;
            }

        } else if (count($botDialogCommand->images) === 1) {
            $this->replyPhoto("<b>" . $msg,
                InputFile::create(storage_path("app/public") . "/images-by-bot-id/" . $botDialogCommand->bot_id . "/" . $botDialogCommand->images[0]),
                $inlineKeyboard
            );
            $isSent = true;
        }


        $this->replyKeyboard(!$isSent ? $msg : 'Варианты ответов',
            !is_null($botDialogCommand->reply_keyboard_id) ?
                $replyKeyboard : []);

        /*   if (!is_null($botDialogCommand->reply_keyboard_id)) {
               $this->replyKeyboard(!$isSent ? $msg : 'Варианты ответов', $replyKeyboard);
               $isSent = true;
           }*/

        /*    if (!$isSent)
                $this->reply($msg);*/


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
            'variables' => [],
            'completed_at' => null,
        ]);

        $this->sendDialogData($botDialogCommand, $botUser);
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
            if (!is_null($botDialogCommand->error_text ?? null)) {

                $errorText = $this->prepareDataWithVariables($botDialogCommand->error_text, $botUser);

                $this->sendMessage($botUser->telegram_chat_id ?? null,
                    $errorText);
            }

            return;
        }

        $tmpSummary = $dialog->summary_input_data ?? [];
        $tmpSummary[] = $text;

        $dialog->current_input_data = $text ?? null;
        $dialog->summary_input_data = $tmpSummary;
        $dialog->completed_at = Carbon::now();

        $tmpVariables = $dialog->variables ?? [];

        $tmpVariables[] = (object)[
            "key" => $botDialogCommand->use_result_as ?? "key_$dialog->id",
            "value" => "$text"
        ];

        $dialog->variables = $tmpVariables;
        $dialog->save();


        if (!is_null($botDialogCommand->store_to ?? null)) {
            $tmp[$botDialogCommand->store_to] = $text ?? null;
            $botUser->update($tmp);
        }

        $flags = is_array($botDialogCommand->result_flags) ? $botDialogCommand->result_flags : json_decode($botDialogCommand->result_flags ?? '[]');
        if (count($flags) > 0) {
            $tmp = [];
            foreach ($flags as $flag) {
                $tmp[$flag] = true;
            }
            $test = $botUser->update($tmp);
        }

        $needStop = false;

        if (!$botDialogCommand->is_empty && !is_null($botDialogCommand->post_text ?? null)) {

            $postText = $this->prepareDataWithVariables($botDialogCommand->post_text, $botUser);
            $this->sendMessage($botUser->telegram_chat_id ?? null,
                $postText);
        }

        $isAnswerFound = false;
        if (count($botDialogCommand->answers ?? []) > 0) {

            $tmpItem = null;
            foreach ($botDialogCommand->answers as $item) {
                if (!is_null($item->answer ?? null)) {
                    if ($text == $item->answer) {
                        $tmpItem = $item;
                        $isAnswerFound = true;
                        break;
                    }
                }

                if (!is_null($item->pattern ?? null)) {
                    if (preg_match($item->pattern, $text)) {
                        $tmpItem = $item;
                        $isAnswerFound = true;
                        break;
                    }
                }
            }

            if (!is_null($tmpItem)) {

                $tmpNextDialog = BotDialogCommand::query()
                    ->where("id", $tmpItem->next_bot_dialog_command_id)
                    ->first();

                BotDialogResult::query()->create([
                    'bot_user_id' => $botUser->id,
                    'bot_dialog_command_id' => $tmpItem->next_bot_dialog_command_id ?? null,
                    'current_input_data' => null,
                    'summary_input_data' => $dialog->summary_input_data ?? [],
                    'variables' => $dialog->variables,
                    'completed_at' => ($tmpNextDialog->is_empty ?? true) ? Carbon::now() : null,
                ]);

                $this->sendDialogData($tmpNextDialog ?? null,
                    $botUser);

                if ($tmpNextDialog->is_inform ?? false) {
                    $this->nextBotDialog(null, $botUser);
                }

                if ($tmpNextDialog->is_empty ?? true)
                    $needStop = true;
            }


            if (is_null($tmpItem)) {
                $nextBotDialogCommand = BotDialogCommand::query()
                    ->find($botDialogCommand->next_bot_dialog_command_id);

                if (is_null($nextBotDialogCommand))
                    $needStop = true;
            }

        }

        if (!is_null($botDialogCommand) &&
            !is_null($botDialogCommand->next_bot_dialog_command_id ?? null) &&
            !$needStop &&
            !$isAnswerFound
        ) {
            $nextBotDialogCommand = BotDialogCommand::query()
                ->find($botDialogCommand->next_bot_dialog_command_id);

            BotDialogResult::query()->create([
                'bot_user_id' => $botUser->id,
                'bot_dialog_command_id' => $nextBotDialogCommand->id,
                'current_input_data' => null,
                'summary_input_data' => $dialog->summary_input_data ?? [],
                'variables' => $dialog->variables,
                'completed_at' => ($nextBotDialogCommand->is_empty ?? true) ? Carbon::now() : null,
            ]);

            $needStop = false;

            $this->sendDialogData($nextBotDialogCommand ?? null,
                $botUser);

            if ($nextBotDialogCommand->is_inform ?? false) {
                $this->nextBotDialog(null, $botUser);
            }
            if ($nextBotDialogCommand->is_empty ?? true)
                $needStop = true;
        }


        if ($needStop) {
            $botUser->in_dialog_mode = false;
            $botUser->save();

            $tmp = $dialog->summary_input_data ?? [];


            $this->dialogResponse($botUser, $nextBotDialogCommand, $tmp);
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

        $tmp = $dialogs[count($dialogs) - 1]->summary_input_data ?? [];

        $this->dialogResponse($botUser, $botDialogCommand, $tmp);


    }

    private function dialogResponse($botUser, $botDialogCommand, $dialogData = []): void
    {
        /*     if (!is_null($botDialogCommand->result_channel)) */

        $bot = $this->getSelf();

        $tmpMessage = "Диалог с пользователем <b>#$botUser->id</b> [<b>#$botDialogCommand->id</b>]: \n";

        $step = 1;
        foreach ($dialogData as $data) {
            $tmpMessage .= "Шаг $step: $data \n";

            $step++;
        }


        if (!is_null($botDialogCommand->rules ?? null)) {
            $variables = $this->getVariables($botUser);

            $rules = $botDialogCommand->rules;

            foreach ($rules as $rule) {
                $rule = (object)$rule;

                $arg1 = Collection::make($variables)
                    ->where("key", $rule->param1)
                    ->first()["value"] ?? $rule->param1 ?? false;

                $arg2 = Collection::make($variables)
                    ->where("key", $rule->param2)
                    ->first()["value"] ?? $rule->param2 ?? false;

                $op = $rule->operation;

                switch ($op) {
                    default:
                    case 0:
                        $result = true;
                        break;
                    case 1:
                        $result = $arg1 > $arg2;
                        break;
                    case 2:
                        $result = $arg1 < $arg2;
                        break;
                    case 3:
                        $result = $arg1 == $arg2;
                        break;
                    case 4:
                        $result = $arg1 != $arg2;
                        break;
                    case 5:
                        $arg3 = $arg1 + $arg2;
                        if (!is_null($rule->use_result_as ?? null))
                            $variables[] = (object)[
                                "key" => $rule->use_result_as,
                                "value" => $arg3
                            ];
                        $result = true;
                        break;

                    case 6:
                        $arg3 = $arg1 - $arg2;
                        if (!is_null($rule->use_result_as ?? null))
                            $variables[] = (object)[
                                "key" => $rule->use_result_as,
                                "value" => $arg3
                            ];
                        $result = true;
                        break;

                    case 7:
                        $result = $arg1 && $arg2;
                        break;
                    case 8:
                        $result = $arg1 || $arg2;
                        break;
                }


                if ($result && !is_null($rule->text_if_true ?? null)) {
                    $text = $this->prepareDataWithVariables($rule->text_if_true, $botUser);
                    $this->replyInlineKeyboard($text, $rule->keyboard_if_true ?? []);
                }

                if (!$result && !is_null($rule->text_if_false ?? null)) {
                    {
                        $text = $this->prepareDataWithVariables($rule->text_if_false, $botUser);
                        $this->replyInlineKeyboard($text, $rule->keyboard_if_false ?? []);
                    }
                }

            }

            $channel = $botDialogCommand->result_channel ??
                $bot->order_channel ??
                null;

            $tmpMessage .= "Пользователь:\n"
                . "-ТГ id: " . ($botUser->telegram_chat_id ?? '-') . "\n"
                . "-имя из ТГ: " . ($botUser->fio_from_telegram ?? 'Имя из телеграм не указано') . "\n"
                . "-введенное имя: " . ($botUser->name ?? 'Введенное имя не указано') . "\n"
                . "-телефон: " . ($botUser->phone ?? 'Номер телефона не указан') . "\n"
                . "-email: " . ($botUser->email ?? 'Почта не указана') . "\n";

            $thread = $bot->topics["questions"] ?? null;

            $botDomain = $bot->bot_domain;
            $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $botUser->telegram_chat_id);


            //$this->sendMessage($channel, $tmpMessage, $thread);

            $this->sendInlineKeyboard($channel,
                $tmpMessage,
                [
                    [
                        ["text" => "✉Написать пользователю ответ", "url" => $link]
                    ]
                ],
                $thread
            );
        }

    }
}
