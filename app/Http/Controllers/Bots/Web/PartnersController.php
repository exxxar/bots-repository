<?php

namespace App\Http\Controllers\Bots\Web;

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
            ->list($request->all());
    }

    public function partnersCategories(Request $request): \Illuminate\Database\Eloquent\Collection|array
    {
        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->listOfPartnersCategories();
    }

    public function updateSettings(Request $request){

        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->updateSettings($request->all());
    }

    public function changeStatus(Request $request){
        $request->validate([
            "product_id" => "required",
            "partner_id" => "required",
            "status" => "required",
        ]);

        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->changeStatus($request->all());
    }


    /**
     * @throws ValidationException
     */
    public function store(Request $request): \App\Http\Resources\PartnerResource
    {
        $request->validate([
            "telegram_domain" => "required",
        ]);

        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->create($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request): \App\Http\Resources\PartnerResource
    {
        $request->validate([
            'id'=> "required",
            'bot_partner_id'=> "required",
            'title'=> "",
            'description'=> "",
            'image'=> "",
            'is_active'=> "",
            'extra_charge'=> "",
            'config'=> "",
            'legal_info'=> "",
        ]);

        return BusinessLogic::partners()
            ->setBot($request->bot ?? null)
            ->update($request->all());


    }

    public function destroy(Request $request)
    {

        $bot = $request->bot;


    }
}
