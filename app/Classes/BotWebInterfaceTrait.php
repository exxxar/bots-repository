<?php

namespace App\Classes;

use Carbon\Carbon;

trait BotWebInterfaceTrait
{
    protected $webMessages = [];

    protected $isWebMode = false;

    public function pushWebMessage($message)
    {

        $message["created_at"] = Carbon::now()
            ->format('Y-m-d H:i:s');

        $this->webMessages[] = $message;
    }
}
