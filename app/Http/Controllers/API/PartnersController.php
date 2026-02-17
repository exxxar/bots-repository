<?php

namespace App\Http\Controllers\API;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Models\AmoCrm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PartnersController extends Controller
{

    public function index(Request $request): \App\Http\Resources\PartnerCollection
    {
        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->list($request->all(), true);
    }

    public function togglePartnersInFavorites(Request $request)
    {
        $request->validate([
            "id" => "required"
        ]);

        return response()
            ->json([
                "fav_partners" => BusinessLogic::partners()
                    ->setBot($request->bot ?? null)
                    ->setBotUser($request->botUser ?? null)
                    ->togglePartnerInFavorites($request->id)
            ]);
    }

    public function partnersCategories(Request $request): \Illuminate\Database\Eloquent\Collection|array
    {
        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->listOfPartnersCategories();
    }


}
