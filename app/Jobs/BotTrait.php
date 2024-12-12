<?php

namespace App\Jobs;

use App\Models\Bot;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

trait BotTrait
{
    /**
     * @throws TelegramSDKException
     */
    protected function initApi(): ?Api
    {
        $bot = Bot::query()
            ->where("id", $this->botId)
            ->first();

        if (is_null($bot) || mb_strlen($this->message ?? $this->caption ??  '') == 0)
            return null;

        $token = env("APP_DEBUG") ?
            ($bot->bot_token_dev ?? null) :
            ($bot->bot_token ?? $bot->bot_token_dev ?? null);


        return new Api($token);
    }

    protected function messageAutFormat() : void {
        if (!is_null($this->caption ?? null)) {
            $this->caption = mb_strlen($this->caption ?? '') >= 1024 ?
                mb_substr($this->caption, 0, 1024) : $this->caption;
        }

        if (!is_null($this->message ?? null)) {
            $this->message = mb_strlen($this->message ?? '') >= 4096 ?
                mb_substr($this->message, 0, 4096) : $this->message;
        }

    }
    protected function attachElements(array $tmp): array
    {
        if (!is_null($this->messageThreadId ?? null))
            $tmp["message_thread_id"] = $this->messageThreadId;

        if (!empty($this->inlineKeyboard ?? [])) {

            $tmp['reply_markup'] = json_encode([
                'inline_keyboard' => $this->inlineKeyboard,
            ]);
        }

        if (!empty($this->replyKeyboard ?? [])) {

            $tmp["reply_markup"] = !empty($this->replyKeyboard) ? json_encode([
                'keyboard' => $this->replyKeyboard,
                'resize_keyboard' => $this->keyboardSettings["resize_keyboard"] ?? true,
                'is_persistent' => $this->keyboardSettings["is_persistent"] ?? false,
                'one_time_keyboard' => $this->keyboardSettings["one_time_keyboard"] ?? false,
                'input_field_placeholder' => $this->keyboardSettings["input_field_placeholder"] ?? "Выбор действия"
            ]) : json_encode([
                'remove_keyboard' => true,
            ]);

        }

        return $tmp;

    }
}
