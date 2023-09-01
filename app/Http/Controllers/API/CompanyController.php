<?php

namespace App\Http\Controllers\API;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Bot;
use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyController extends Controller
{
    public function index(Request $request): \App\Http\Resources\CompanyCollection
    {
        return BusinessLogic::companies()
            ->list($request->get("search") ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function loadLocations(Request $request, $companyId = null): \App\Http\Resources\LocationCollection
    {
        return BusinessLogic::companies()
            ->locationsList($companyId);
    }

}
