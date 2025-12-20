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

        $bot = $request->bot ?? Bot::query()->find($request->bot_id ?? null);

        return BusinessLogic::frontPad()
            ->setBot($bot)
            ->store($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function updateProducts(Request $request)
    {
        $bot = $request->bot ?? Bot::query()->find($request->bot_id ?? null);

        BusinessLogic::frontPad()
            ->setBot($bot)
            ->loadProducts();

        return \response()->noContent();
    }


    /**
     * @throws ValidationException
     */
    public function import(Request $request)
    {

        $request->validate([
            "excel_file" => "required"
        ]);

        $file = $request->file('excel_file');


        $bot = $request->bot ?? Bot::query()->find($request->bot_id ?? null);

        BusinessLogic::frontPad()
            ->setBot($bot)
            ->import($file);

        return response()->noContent();
    }
}
