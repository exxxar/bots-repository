<?php

namespace App\Http\Controllers\Admin;

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

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $size = $request->get("size") ?? config('app.results_per_page');

        $search = $request->search ?? null;

        $companies = Company::query()
            ->withTrashed();

        if (!is_null($search))
            $companies = $companies->where("title", 'like', "%$search%")
                ->orWhere("slug", "like", "%$search%");

        $companies = $companies
            ->orderBy("updated_at","DESC")
            ->paginate($size);

        return CompanyResource::collection($companies);
    }

    public function store(CompanyStoreRequest $request): Response
    {
        $company = Company::create($request->validated());

        return new CompanyResource($company);
    }

    public function show(Request $request, Company $company): Response
    {
        return new CompanyResource($company);
    }

    public function update(CompanyUpdateRequest $request, Company $company): Response
    {
        $company->update($request->validated());

        return new CompanyResource($company);
    }

    public function destroy(Request $request, $companyId)
    {
        $company = Company::query()->find($companyId);

        if (is_null($company))
            return response()->noContent(404);

        $company->deleted_at = Carbon::now();
        $company->save();

        return response()->noContent();
    }

    public function restore(Request $request, $companyId)
    {
        $company = Company::query()
            ->withTrashed()
            ->find($companyId);

        if (is_null($company))
            return response()->noContent(404);

        $company->deleted_at = null;
        $company->save();

        return response()->noContent();
    }

    public function createCompany(Request $request)
    {
        $request->validate([
            'title' => "required|string:255",
            'slug' => "required|string:190|unique:companies,slug",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
        ]);

        $creatorId = Auth::user()->id ?? null;

        $imageName = null;
        if ($request->hasFile('company_logo')) {

            $file = $request->file('company_logo');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $file->storeAs("/public/companies/$request->slug/$imageName");

        }

        $tmp = (object)$request->all();


        $tmp->links = json_decode($tmp->links);
        $tmp->schedule = json_decode($tmp->schedule);
        $tmp->phones = json_decode($tmp->phones);
        $tmp->image = $imageName;
        $tmp->creator_id = $creatorId;
        $tmp->owner_id = $creatorId;

        $company = Company::query()->create((array)$tmp);

        return new CompanyResource($company);
    }

    public function editCompany(Request $request)
    {
        $request->validate([
            'title' => "required|string:255",
            'slug' => "required|string:190",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
        ]);

        $creatorId = Auth::user()->id ?? null;

        $imageName = $request->image ?? null;

        if (isset($request->removed_image)){
            unlink(storage_path()."/app/public/companies/$request->slug/$request->removed_image");
        }

        if ($request->hasFile('company_logo')) {

            $file = $request->file('company_logo');

            $ext = $file->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $file->storeAs("/public/companies/$request->slug/$imageName");

        }

        $tmp = (object)$request->all();

        $tmp->links = json_decode($tmp->links);
        $tmp->schedule = json_decode($tmp->schedule);
        $tmp->phones = json_decode($tmp->phones);
        $tmp->image = $imageName;
        $tmp->creator_id = $creatorId;
        $tmp->owner_id = $creatorId;

        $company = Company::query()->where("id", $request->id)
            ->first();

        if (is_null($company))
            return response()->noContent(404);

        $company->update ((array)$tmp);

        return new CompanyResource($company);
    }
}
