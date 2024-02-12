<?php

use App\Http\Controllers\Bots\InlineBotController;

\App\Facades\InlineQueryService::inline()
    ->controller(InlineBotController::class)
    ->query("меню", "baseMenu")
    ->query("админы", "inlineHandler");
