<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\BitrixStoreRequest;
use App\Http\Requests\BitrixUpdateRequest;
use App\Http\Resources\BitrixCollection;
use App\Http\Resources\BitrixResource;
use App\Models\Bitrix;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class BitrixController extends Controller
{


    /**
     * @throws ValidationException
     */
    public function store(Request $request): BitrixResource
    {
        $request->validate([
            "url" => "required",

        ]);

        return BusinessLogic::bitrix()
            ->setBot($request->bot ?? null)
            ->store($request->all());
    }


}
