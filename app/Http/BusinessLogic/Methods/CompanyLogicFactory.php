<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;

use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Company;
use App\Models\Location;
use Carbon\Carbon;

use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CompanyLogicFactory
{
    use LogicUtilities;

    public function get($companyId): CompanyResource
    {
        $company = Company::query()
            ->where("id", $companyId)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найден");

        return new CompanyResource($company);
    }

    public function list($search = null, $size = null): CompanyCollection
    {
        $size = $size ?? config('app.results_per_page');

        $companies = Company::query()
            ->withTrashed();

        if (!is_null($search))
            $companies = $companies->where("title", 'like', "%$search%")
                ->orWhere("slug", "like", "%$search%");

        $companies = $companies
            ->orderBy("updated_at", "DESC")
            ->paginate($size);

        return new CompanyCollection($companies);
    }

    /**
     * @throws HttpException
     */
    public function destroy($companyId): CompanyResource
    {
        $company = Company::query()->find($companyId);

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найден");

        $company->deleted_at = Carbon::now();
        $company->save();

        return new CompanyResource($company);
    }

    /**
     * @throws HttpException
     */
    public function restore($companyId): CompanyResource
    {
        $company = Company::query()
            ->withTrashed()
            ->find($companyId);

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найден");

        $company->deleted_at = null;
        $company->save();

        return new CompanyResource($company);
    }

    /**
     * @throws ValidationException
     */
    public function createCompany(array $data, $companyLogo = null, $creatorId = null): CompanyResource
    {
        $validator = Validator::make($data, [
            'title' => "required|string:255",
            'slug' => "required|string:190|unique:companies,slug",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
            'vat_code' => "required|integer",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        if (!is_null($companyLogo))
            $imageName = $this->uploadPhoto("/public/companies/" . $data["slug"], $companyLogo);

        $tmp = (object)$data;

        $tmp->links = json_decode($tmp->links);
        $tmp->schedule = json_decode($tmp->schedule);
        $tmp->phones = json_decode($tmp->phones);
        $tmp->image = $imageName ?? null;
        $tmp->creator_id = $creatorId;
        $tmp->owner_id = $creatorId;

        $company = Company::query()->create((array)$tmp);

        return new CompanyResource($company);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function editCompany(array $data, $companyLogo = null, $creatorId = null): CompanyResource
    {

        $validator = Validator::make($data, [
            'title' => "required|string:255",
            'slug' => "required|string:190",
            'description' => "required|string:255",
            'address' => "required|string:255",
            'email' => "required|string:255",
            'vat_code' => "required|integer",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $imageName = $data["image"] ?? null;

        if (isset($data["removed_image"]))
            unlink(storage_path() . "/app/public/companies/" . $data["slug"] . "/" . $data["removed_image"]);


        if (!is_null($companyLogo))
            $imageName = $this->uploadPhoto("/public/companies/" . $data["slug"], $companyLogo);


        $tmp = (object)$data;

        $tmp->links = json_decode($tmp->links);
        $tmp->schedule = json_decode($tmp->schedule);
        $tmp->phones = json_decode($tmp->phones);
        $tmp->image = $imageName;
        $tmp->creator_id = $creatorId;
        $tmp->owner_id = $creatorId;

        $company = Company::query()->where("id", $data["id"])
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $company->update((array)$tmp);

        return new CompanyResource($company);
    }

    public function locationsList($companyId): LocationCollection
    {
        $locations = Location::query()
            ->where("company_id", $companyId)
            ->get();

        return new LocationCollection($locations);
    }


    /**
     * @throws HttpException
     */
    public function createOrUpdateLocation($companyId, array $data, array $uploadedPhotos = null): LocationResource
    {

        $company = Company::query()->where("id", $companyId)
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найден");

        if (isset($data["deleted_locations"])) {

            $deleteds = json_decode($data["deleted_locations"]);

            foreach ($deleteds as $id) {
                $dlocation = Location::query()->find($id);
                if (!is_null($dlocation))
                    $dlocation->delete();
            }

        }

        $photos = $this->uploadPhotos("/public/companies/" . $company->slug, $uploadedPhotos);

        $tmp = (object)$data;

        $tmp->images = json_decode($tmp->images ?? '[]');

        if (count($photos) > 0)
            $tmp->images = $photos;

        $tmp->is_active = true;
        $tmp->can_booking = $data["can_booking"] == "true" ? true : false;

        $locationId = $tmp["id"] ?? null;
        if (!is_null($locationId)) {
            $location = Location::query()
                ->where("id", $locationId)
                ->first();

            if (!is_null($location))
                $location->update((array)$tmp);
        } else {
            $location = Location::query()->create((array)$tmp);
        }

        return new LocationResource($location);
    }
}
