<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\LocationResource;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function index(Request $request): \App\Http\Resources\CompanyCollection
    {

        return BusinessLogic::companies()
            ->list($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function loadCompany(Request $request): CompanyResource
    {
        $bot = $request->bot ?? null;

        return BusinessLogic::companies()
            ->get($bot->company_id ?? null);

    }

    /**
     * @throws ValidationException
     */
    public function editCompany(Request $request): CompanyResource
    {
        $request->validate([
            'title' => "required|string:255",
            'slug' => "required|string:190",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
            'vat_code' => "required|integer",
        ]);

        $botUser = $request->botUser;

        return BusinessLogic::companies()
            ->editCompany($request->all(),
                $request->hasFile('company_logo') ? $request->file('company_logo') : null,
                $botUser->user_id
            );
    }

    public function loadLocations(Request $request): \App\Http\Resources\LocationCollection
    {
        $bot = $request->bot;
        return BusinessLogic::companies()
            ->locationsList($bot->company_id ?? null);
    }

    public function createLocation(Request $request): LocationResource
    {
        $bot = $request->bot;

        return BusinessLogic::companies()
            ->createOrUpdateLocation(
                $bot->company_id,
                $request->all(),
                $request->hasFile('files') ? $request->file('files') : null
            );
    }
}
