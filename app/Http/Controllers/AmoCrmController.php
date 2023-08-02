<?php

namespace App\Http\Controllers;

use App\Http\Requests\AmoCrmStoreRequest;
use App\Http\Requests\AmoCrmUpdateRequest;
use App\Http\Resources\AmoCrmCollection;
use App\Http\Resources\AmoCrmResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AmoCrmController extends Controller
{

    public function testAmoCrm(Request $request)
    {

    }

    public function saveAmoCrm(Request $request)
    {
        $request->validate([
            "client_id" => "required",
            "client_secret" => "required",
            "auth_code" => "required",
            "subdomain" => "required",
            "bot_id" => "required",
        ]);


        $bot = Bot::query()->find($request->bot_id);

        if (is_null($bot))
            return response()->noContent(404);

        //$redirectUri = 'https://your-cashman.com/crm/amo/' . $bot->bot_domain;

        AmoCrm::query()->updateOrCreate(
            [
                'bot_id' => $request->bot_id,
            ],
            [
                'client_id' => $request->client_id,
                'client_secret' => $request->client_secret,
                'auth_code' => $request->auth_code,
                'redirect_uri' => $bot->bot_domain,
                'subdomain' => $request->subdomain,
            ]);

        return response()->noContent();
    }
}
