<?php

namespace App\Http\Controllers\Admin;

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
        $logic = BusinessLogic::companies();

        if ($request->botUser->is_manager&&!$request->botUser->is_admin)
            $logic = $logic->setBotUser($request->botUser);

        return $logic
            ->list($request->search ?? null,
                $request->get("size") ?? config('app.results_per_page')
            );
    }

    public function destroy(Request $request, $companyId): CompanyResource
    {
        return BusinessLogic::companies()
            ->destroy($companyId);
    }

    public function restore(Request $request, $companyId): CompanyResource
    {

        return BusinessLogic::companies()
            ->restore($companyId);
    }

    /**
     * @throws ValidationException
     */
    public function createCompany(Request $request): CompanyResource
    {
        $request->validate([
            'title' => "required|string:255",
            'slug' => "required|string:190|unique:companies,slug",
            'description' => "required|string:255",
           // 'address' => "required|string:255",
            //'email' => "required|string:255",
            'vat_code' => "required|integer",
        ]);

        return BusinessLogic::companies()
            ->setBotUser($request->botUser ?? null)
            ->createCompany($request->all(),
                $request->hasFile('company_logo') ? $request->file('company_logo') : null
            );
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
           // 'address' => "required|string:255",
           // 'email' => "required|string:255",
            'vat_code' => "required|integer",
        ]);

        return BusinessLogic::companies()
            ->setBotUser($request->botUser ?? null)
            ->editCompany($request->all(),
                $request->hasFile('company_logo') ? $request->file('company_logo') : null,
                Auth::user()->id ?? null
            );
    }
}
