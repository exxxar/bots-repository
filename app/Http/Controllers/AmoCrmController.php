<?php

namespace App\Http\Controllers;

use App\Facades\BusinessLogic;
use App\Http\Requests\AmoCrmStoreRequest;
use App\Http\Requests\AmoCrmUpdateRequest;
use App\Http\Resources\AmoCrmCollection;
use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AmoCrmController extends Controller
{

    public function testAmoCrm(Request $request)
    {

    }

    /**
     * @throws ValidationException
     */
    public function saveAmoCrm(Request $request)
    {
        $request->validate([
            "client_id" => "required",
            "client_secret" => "required",
            "auth_code" => "required",
            "subdomain" => "required",
            "bot_id" => "required",
        ]);


        $bot = Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::amo()
            ->setBot($bot)
            ->createOrUpdate($request->all());
    }
}
