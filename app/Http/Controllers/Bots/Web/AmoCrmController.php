<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\AmoCrm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class AmoCrmController extends Controller
{

    public function testAmoCrm(Request $request)
    {

    }

    public function syncAmoCrm(Request $request){

        $bot = $request->bot;

        $data = (object)[
            "clientId" => $bot->amo->client_id ?? null,
            "clientSecret" => $bot->amo->client_secret ?? null,
            "authCode" => $bot->amo->auth_code ?? null,
            "domain" => 'https://your-cashman.com/crm/amo/' . $bot->amo->redirect_uri,
            "subdomain" => $bot->amo->subdomain ?? null,
        ];
        Log::info("amo1 ".print_r($data, true));
        $amo = new \App\Integrations\AmoCRMIntegration($data);

       $amo->firstOAuth();
        //$amo->nextOAuth($bot);
    }

    /**
     * @throws ValidationException
     */
    public function saveAmoCrm(Request $request): \App\Http\Resources\AmoCrmResource
    {
        $request->validate([
            "client_id" => "required",
            "client_secret" => "required",
            "auth_code" => "required",
            "subdomain" => "required",
        ]);

        return BusinessLogic::amo()
            ->setBot($request->bot ?? null)
            ->createOrUpdate($request->all());


    }
}
