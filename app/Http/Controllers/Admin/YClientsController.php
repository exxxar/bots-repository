<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\YClientsStoreRequest;
use App\Http\Requests\YClientsUpdateRequest;
use App\Models\Bot;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class YClientsController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function saveYClients(Request $request): \App\Http\Resources\YClientResource
  {
      $request->validate([
          "bot_id"=>"required",
          "login" => "required",
          "password" => "required",
          "partner_token" => "required",
      ]);

      $bot = Bot::query()->find($request->bot_id);

      return BusinessLogic::yClients()
          ->setBot( $bot ?? null)
          ->createOrUpdate($request->all());

  }
}
