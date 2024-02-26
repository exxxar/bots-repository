<?php

namespace App\Http\Controllers\Admin;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\PromoCodeStoreRequest;
use App\Http\Requests\PromoCodeUpdateRequest;
use App\Http\Resources\PromoCodeCollection;
use App\Http\Resources\PromoCodeResource;
use App\Models\Bot;
use App\Models\PromoCode;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class PromoCodeController extends Controller
{
    public function index(Request $request): PromoCodeCollection
    {
        $request->validate([
            "bot_id" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->listOfPromoCodes(
                $request->search ?? null,
                $request->size ?? 12,
                $request->order ?? "updated_at",
                $request->direction ?? "desc"
            );
    }


    /**
     * @throws ValidationException
     */
    public function activate(Request $request): object
    {
        $request->validate([
            "bot_id" => "required",
            "code" => "required",
        ]);

        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->activatePromoCode(
               $request->all()
            );
    }
    /**
     * @throws ValidationException
     */
    public function store(Request $request): PromoCodeResource
    {
        $request->validate([
            "bot_id" => "required",
            'code' => "required",
        ]);


        $bot = Bot::query()
            ->where("id", $request->bot_id ?? null)
            ->first();

        return BusinessLogic::promoCodes()
            ->setBot($bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->store($request->all());
    }

    public function results(Request $request, PromoCode $promoCode): Response
    {
        return new PromoCodeResource($promoCode);
    }


    public function remove(Request $request, $promoCodeId): PromoCodeResource
    {
        return BusinessLogic::promoCodes()
            ->removePromoCode($promoCodeId);
    }
}
