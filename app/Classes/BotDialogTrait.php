<?php

namespace App\Classes;

use App\Exports\BotUsersExport;
use App\Exports\DialogAnswersExport;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Models\BotDialogCommand;
use App\Models\BotDialogResult;
use App\Models\BotMenuTemplate;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
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

            $content = str_replace($variable->key, $variable->custom_stored_value ?? $variable->value ?? 'не указано', $content);
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

        $tmpVariables = $dialog->variables ?? [];

        $var = (object)[
            "key" => $botDialogCommand->use_result_as ?? "key_$dialog->id",
            "value" => "$text",
            "need_print" => count($botDialogCommand->answers ?? []) == 0,
            "custom_stored_value" => null,
        ];

        $dialog->current_input_data = is_null($text ?? null) ? null : (object)[
            "text" => $text,
            "question_text" => $botDialogCommand->pre_text ?? null,
            "question_id" => $botDialogCommand->id ?? null,
            "variable"=>$var
        ];

        $tmpSummary[] = $dialog->current_input_data;

        $dialog->summary_input_data = $tmpSummary;
        $dialog->completed_at = Carbon::now();



        if (!is_null($botDialogCommand->custom_stored_value ?? null)) {
            $var->custom_stored_value = $botDialogCommand->custom_stored_value;
        }


        $tmpVariables[] = $var;

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

        $isAnswerFound = false;
        if (count($botDialogCommand->answers ?? []) > 0) {

            $tmpItem = null;
            foreach ($botDialogCommand->answers as $item) {

                if (!is_null($item->answer ?? null)) {

                    if (mb_strtolower(trim($text)) == mb_strtolower(trim($item->answer))) {

                        Log::info("совпал ответ $text == $item->answer " . print_r($item->toArray(), true));

                        $tmpItem = (object)$item;
                        $tmpItem->need_print = $item->need_print ?? false;
                        $isAnswerFound = true;
                        break;
                    }
                }

                if (strlen(trim($item->pattern ?? '')) > 0) {
                    if (preg_match($item->pattern, $text)) {
                        $tmpItem = (object)$item;
                        $tmpItem->need_print = $item->need_print ?? false;
                        $isAnswerFound = true;
                        break;
                    }
                }


            }

            if (!is_null($tmpItem)) {

                $tmpNextDialog = BotDialogCommand::query()
                    ->where("id", $tmpItem->next_bot_dialog_command_id)
                    ->first();

                //if (!is_null($tmpItem->custom_stored_value ?? null)) {

                $tmpV = $botDialogCommand->use_result_as ?? null;
                $tmpVariables = $dialog->variables ?? [];

                for ($index = 0; $index < count($tmpVariables); $index++) {
                    $var = (object)$tmpVariables[$index];

                    if ($var->key == $tmpV || $var->key == "key_$dialog->id") {
                        $var->custom_stored_value = $tmpItem->custom_stored_value ?? null;
                        $var->need_print = $tmpItem->need_print ?? false;
                    }
                    $tmpVariables[$index] = $var;
                }

                $dialog->variables = $tmpVariables;
                //    $dialog->save();
                // }

                Log::info("viriables_on_step=>" . print_r($dialog->variables, true));

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


            /* if (is_null($tmpItem)) {
                 $nextBotDialogCommand = BotDialogCommand::query()
                     ->find($botDialogCommand->next_bot_dialog_command_id);

                 if (is_null($nextBotDialogCommand))
                     $needStop = true;
             }*/

        }

        if (!$botDialogCommand->is_empty &&
            !is_null($botDialogCommand->post_text ?? null)
            && !$isAnswerFound
        ) {

            $postText = $this->prepareDataWithVariables($botDialogCommand->post_text, $botUser);
            $this->sendMessage($botUser->telegram_chat_id ?? null,
                $postText);
        }

        if (!is_null($botDialogCommand) &&
            !is_null($botDialogCommand->next_bot_dialog_command_id ?? null) &&
            !$needStop &&
            !$isAnswerFound
        ) {
            $nextBotDialogCommand = BotDialogCommand::query()
                ->find($botDialogCommand->next_bot_dialog_command_id);

            if (!is_null($botDialogCommand->custom_stored_value ?? null)) {
                $tmpV = $botDialogCommand->use_result_as ?? null;
                $tmpVariables = $dialog->variables ?? [];

                for ($index = 0; $index < count($tmpVariables); $index++) {
                    $var = (object)$tmpVariables[$index];

                    if ($var->key == $tmpV) {
                        $var->custom_stored_value = $botDialogCommand->custom_stored_value ?? null;
                        $tmpVariables[$index] = $var;
                    }

                }

                $dialog->variables = $tmpVariables;
                //  $dialog->save();
            }

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
            $tmpVariables = $dialog->variables ?? [];

            $nextBotDialogCommand = BotDialogCommand::query()
                ->find($botDialogCommand->next_bot_dialog_command_id);

            $this->dialogResponse($botUser, $nextBotDialogCommand, $tmp, $tmpVariables);
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
        $tmpVariables = $dialogs[count($dialogs) - 1]->variables ?? [];

        $this->dialogResponse($botUser, $botDialogCommand, $tmp, $tmpVariables);


    }


    private function dialogResponse($botUser, $botDialogCommand, $dialogData = [], $variables = []): void
    {
        /*     if (!is_null($botDialogCommand->result_channel)) */

        if (is_null($botUser ?? null) || is_null($botDialogCommand ?? null))
            return;

        $bot = $this->getSelf();

        $resultData = "Диалог с пользователем <b>#$botUser->id</b> [<b>#$botDialogCommand->id</b>]: \n";

        $step = 1;
        foreach ($dialogData as $data) {
            $data = (object)$data;

            if (!is_null($data->question_text ?? null)) {
                $resultData .= ($data->question_id ?? $step) . "=>" . ($data->text ?? '-') . "\n";
                Log::info(print_r($data, true));
            }


            if (is_string($data))
                $resultData .= "Шаг $step: $data \n";

            $step++;
        }

        foreach ($variables as $data) {
            $data = (object)$data;

            if ($data->need_print ?? false) {
                $resultData .= $data->key . "=" . $data->value . "(" . ($data->custom_stored_value ?? '-') . ")\n";
            }
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
                        $this->replyInlineKeyboard($text, $rule->keyboard_if_true ?? []);

                    }
                }

            }

        }

        $channel = $botDialogCommand->result_channel ??
            $bot->order_channel ??
            null;


        $sendByText = (boolean)($botDialogCommand->send_params["send_by_text"] ?? true);
        $sendToEmail = (boolean)($botDialogCommand->send_params["send_to_mail"] ?? false);
        $sendByFile = (boolean)($botDialogCommand->send_params["send_by_file"] ?? false);
        $format = $botDialogCommand->send_params["format"] ?? null;
        $mail = $botDialogCommand->send_params["mail"] ?? null;

        $tmpMessage = "Диалог <b>#$botDialogCommand->id</b>\nПользователь:\n"
            . "-ТГ id: " . ($botUser->telegram_chat_id ?? '-') . "\n"
            . "-имя из ТГ: " . ($botUser->fio_from_telegram ?? 'Имя из телеграм не указано') . "\n"
            . "-введенное имя: " . ($botUser->name ?? 'Введенное имя не указано') . "\n"
            . "-телефон: " . ($botUser->phone ?? 'Номер телефона не указан') . "\n"
            . "-email: " . ($botUser->email ?? 'Почта не указана') . "\n\n"
            . "Данные из диалога:\n";

        if ($sendByText) {
            if (strlen(trim($format ?? '')) > 0)
                $tmpMessage .= $this->prepareDataWithVariables($format, $botUser);
            else
                $tmpMessage .= $resultData;
        }


        $thread = $bot->topics["questions"] ?? null;

        $botDomain = $bot->bot_domain;
        $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $botUser->telegram_chat_id);

        $this->sendInlineKeyboard($channel,
            $tmpMessage,
            [
                [
                    ["text" => "✉Написать пользователю ответ", "url" => $link]
                ]
            ],
            $thread
        );

        if ($sendByFile) {
            $fileName = Str::uuid() . ".xlsx";

            Excel::store(new DialogAnswersExport([
                "answers" => $variables ?? [],
                "user" => (object)[
                    "telegram_chat_id" => $botUser->telegram_chat_id,
                    "fio_from_telegram" => $botUser->fio_from_telegram,
                    "phone" => $botUser->phone,
                ]
            ]), "$fileName", "public", \Maatwebsite\Excel\Excel::XLSX);

            $date = Carbon::now()->format("Y-m-d H-i-s");

            $this
                ->sendDocument($channel,
                    "Результат от пользователя #"
                    . ($botUser->telegram_chat_id ?? '-')
                    . " ("
                    . ($botUser->fio_from_telegram ?? 'имя не указано') . ")",
                    InputFile::create(
                        storage_path("app/public") . "/$fileName",
                        "dialog-answers-$date.xlsx"
                    ),
                    $thread
                );

            unlink(storage_path("app/public") . "/$fileName");
        }

        if ($sendToEmail) {
            //отправляем письмо на почту $tmpMessage

            if (is_null($mail))
                return;


            $data = [
                "answers" => $variables ?? [],
                "user" => (object)[
                    "telegram_chat_id" => $botUser->telegram_chat_id,
                    "fio_from_telegram" => $botUser->fio_from_telegram,
                    "phone" => $botUser->phone,
                ]
            ];

            Mail::send('emails.result', $data, function ($message) use ($botDomain, $mail, $botDialogCommand, $botUser) {
                $message->to($mail, $botDomain)->subject("Диалог #$botDialogCommand->id - от пользователя " . ($botUser->telegram_chat_id ?? '-'));
                $message->from(env("APP_EMAIL"), 'YourCashman:' . $botDomain);
            });
        }


    }
}
