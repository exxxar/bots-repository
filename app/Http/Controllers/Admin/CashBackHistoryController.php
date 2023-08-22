<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\CashBackHistoryStoreRequest;
use App\Http\Requests\CashBackHistoryUpdateRequest;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\CashBackHistoryResource;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBackHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class CashBackHistoryController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function index(Request $request): \App\Http\Resources\CashBackHistoryCollection
    {
        $request->validate([
            "user_telegram_chat_id" => "required"
        ]);

        $size = $request->get("size") ?? config('app.results_per_page');

        return BusinessLogic::administrative()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->cashBackHistoryList($request->all(), $size);
    }

    /**
     * @throws ValidationException
     */
    public function receiver(Request $request): BotUserResource
    {
        $request->validate([
            "user_telegram_chat_id" => "required"
        ]);

        return BusinessLogic::administrative()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->cashbackReceiver($request->all());
    }
}
