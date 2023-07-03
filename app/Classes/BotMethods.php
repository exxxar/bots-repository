<?php

namespace App\Classes;

use App\Models\Bot;
use App\Models\BotTextContent;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;

class BotMethods
{

    use BotWebInterfaceTrait, BotBaseMethodsTrait;

    private $bot;

    private $domain;

    public function bot()
    {
        return $this;
    }

    public function whereId($value)
    {
        $bot = Bot::query()->where("id", $value)->first();

        if (is_null($bot))
            return $this;

        $token = env("APP_DEBUG") ?
            $bot->bot_token_dev : $bot->bot_token;

        $this->bot = new Api($token);

        $this->domain = $bot->bot_domain;

        return $this;
    }

    public function whereDomain($value)
    {
        $bot = Bot::query()->where("bot_domain", $value)->first();
        $this->bot = new Api(env("APP_DEBUG") ?
            $bot->bot_token_dev : $bot->bot_token);

        $this->domain = $bot->bot_domain;

        return $this;
    }

    public function prepareUserName($botUser)
    {
        return ($botUser->fio_from_telegram ??
            $botUser->name ??
            $botUser->phone ??
            $botUser->email ??
            $botUser->telegram_chat_id ?? $botUser->id);
    }

    public function allText(){

        if (is_null($this->bot))
            return [];

        $content = BotTextContent::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return $content->toArray();
    }


}
