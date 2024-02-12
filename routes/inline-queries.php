<?php

use App\Http\Controllers\Bots\InlineBotController;

\App\Facades\InlineQueryService::bot()
    ->controller(InlineBotController::class)
    ->query("меню", "inlineHandler");
