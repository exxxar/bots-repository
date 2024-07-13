<?php

namespace App\Http\Controllers;

use App\Facades\BusinessLogic;
use App\Http\Requests\FrontPadStoreRequest;
use App\Http\Requests\FrontPadUpdateRequest;
use App\Http\Resources\FrontPadCollection;
use App\Http\Resources\FrontPadResource;
use App\Models\Bot;
use App\Models\FrontPad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class FrontPadController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function saveFrontPad(Request $request): FrontPadResource
    {
        $request->validate([
            //"hook_url" => "required",
            "token" => "required",
            "bot_id" => "required",
        ]);


        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::frontPad()
            ->setBot($bot)
            ->store($request->all());
    }
}
