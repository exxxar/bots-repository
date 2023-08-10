<?php

namespace App\Http\Controllers\Bots;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\LocationResource;
use App\Models\Bot;
use App\Models\Company;
use App\Models\Location;
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

    public function loadCompany(Request $request){
        $bot = $request->bot;

        $company = Company::query()
            ->where("id", $bot->company_id)
            ->first();

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
            'vat_code' => "required|integer",
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

    public function loadLocations(Request $request)
    {
        $bot = $request->bot;

        $locations = Location::query()
            ->where("company_id", $bot->company_id)
            ->get();

        return LocationResource::collection($locations);
    }

    public function createLocation(Request $request): Response
    {


        $bot = $request->bot;

        $company = Company::query()->where("id", $bot->company_id)
            ->first();

        if (is_null($company))
            return response()->noContent(400);

        if (isset($request->deleted_locations)) {

            $deleteds = json_decode($request->deleted_locations);

            foreach ($deleteds as $id) {
                $dlocation = Location::query()->find($id);
                if (!is_null($dlocation))
                    $dlocation->delete();
            }

        }

        $photos = [];

        if ($request->hasFile('files')) {
            $files = $request->file('files');

            foreach ($files as $key => $file) {
                $ext = $file->getClientOriginalExtension();

                $imageName = Str::uuid() . "." . $ext;

                $file->storeAs("/public/companies/$company->slug/$imageName");
                array_push($photos, $imageName);
            }
        }

        $tmp = (object)$request->all();

        $tmp->images = json_decode($tmp->images ?? '[]');

        if (count($photos) > 0)
            $tmp->images = $photos;

        $tmp->is_active = true;
        $tmp->can_booking = $request->can_booking == "true" ? true : false;

        $locationId = $tmp->id ?? null;
        if (!is_null($locationId)) {
            $location = Location::query()
                ->where("id", $locationId)
                ->first();

            if (!is_null($location))
                $location->update((array)$tmp);
        } else {
            Location::query()->create((array)$tmp);
        }

        return response()->noContent();
    }
}
