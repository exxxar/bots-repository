<?php

use App\Http\Controllers\Bots\InlineBotController;

\App\Facades\InlineQueryService::inline()
    ->controller(InlineBotController::class)
    ->query("", "baseMenu")
    ->query("меню", "inlineHandler");
