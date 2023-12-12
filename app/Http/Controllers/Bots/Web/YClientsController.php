<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\YClientsStoreRequest;
use App\Http\Requests\YClientsUpdateRequest;
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
          "login" => "required",
          "password" => "required",
          "partner_token" => "required",
      ]);

      return BusinessLogic::yClients()
          ->setBot( $request->bot ?? null)
          ->createOrUpdate($request->all());

  }
}
