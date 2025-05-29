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
    public function managerCompaniesList(Request $request)
    {
        return BusinessLogic::companies()
            ->setBotUser($request->botUser ?? null)
            ->managerList($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

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

    public function editLawParamsCompany(Request $request) {
        $botUser = $request->botUser ?? null;
        $bot = $request->bot ?? null;

        return BusinessLogic::companies()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->editLawParams($request->all());
    }
    /**
     * @throws ValidationException
     */
    public function editCompany(Request $request): CompanyResource
    {
        $request->validate([
            'id' => "required",
            'title' => "required|string:255",
          //  'slug' => "required|string:190",
            'description' => "required|string:1000",
          //  'address' => "required|string:255",
           // 'email' => "required|string:255",
           // 'vat_code' => "required|integer",
        ]);

        $botUser = $request->botUser ?? null;
        $bot= $request->bot ?? null;

        return BusinessLogic::companies()
            ->setBot($bot ?? null)
            ->setBotUser($botUser ?? null)
            ->editCompany($request->all(),
                $request->hasFile('company_logo') ? $request->file('company_logo') : null);
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

    public function destroy(Request $request, $companyId): CompanyResource
    {
        return BusinessLogic::companies()
            ->destroy($companyId);
    }
}
