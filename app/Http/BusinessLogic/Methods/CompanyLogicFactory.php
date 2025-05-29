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

class CompanyLogicFactory extends BaseLogicFactory
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

    public function managerList($search = null, $size = null): CompanyCollection
    {
        if (is_null($this->botUser))
            throw new HttpException(404, "Активные менеджер должен быть обязательно указан");

        $size = $size ?? config('app.results_per_page');

        $companies = Company::query()
            //->withTrashed()
            // ->whereNotNull("image");
            ->where("creator_id", $this->botUser->id);

        if (!is_null($search))
            $companies = $companies->where("title", 'like', "%$search%")
                ->orWhere("slug", "like", "%$search%");

        $companies = $companies
            ->orderBy("updated_at", "DESC")
            ->paginate($size);

        return new CompanyCollection($companies);
    }

    public function list($search = null, $size = null): CompanyCollection
    {
        $size = $size ?? config('app.results_per_page');

        $companies = Company::query()
            ->withTrashed();

        if (!is_null($search))
            $companies = $companies->where("title", 'like', "%$search%")
                ->orWhere("slug", "like", "%$search%");

        if (!is_null($this->botUser))
            $companies = $companies
                ->where("creator_id", $this->botUser->id);

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
    public function createCompany(array $data, $companyLogo = null): CompanyResource
    {

        if (is_null($this->botUser))
            throw new HttpException(400, "Менеджер не указан!");

        $validator = Validator::make($data, [
            'title' => "required|string:255",
            'slug' => "required|string:190|unique:companies,slug",
            'description' => "required|string:255",
            // 'address' => "required|string:255",
            //'email' => "required|string:255",
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
        $tmp->creator_id = $this->botUser->id;
        $tmp->owner_id = $this->botUser->id;

        $company = Company::query()->create((array)$tmp);

        return new CompanyResource($company);
    }


    /**
     * @throws ValidationException
     */
    public function editLawParams(array $data): CompanyResource
    {
        if (is_null($this->botUser) || is_null($this->bot))
            throw new HttpException(400, "Неверно заданы параметры!");

        $company = Company::query()
            ->find($this->bot->company_id);

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $lawParams = (array)(json_decode($data["law_params"]));

        $config = (array)($company->law_params ?? []);

        $config["selected_type"] = $lawParams["selected_type"] ?? 2;
        $config["full_name"] = $lawParams["full_name"] ?? $config["full_name"] ?? null;
        $config["inn"] = $lawParams["inn"] ?? $config["inn"] ?? null;
        $config["ogrnip"] = $lawParams["ogrnip"] ?? $config["ogrnip"] ?? null;
        $config["name"] = $lawParams["name"] ?? $config["name"] ?? null;
        $config["kpp"] = $lawParams["kpp"] ?? $config["kpp"] ?? null;
        $config["ogrn"] = $lawParams["ogrn"] ?? $config["ogrn"] ?? null;
        $config["phisical_adress"] = $lawParams["phisical_adress"] ?? $config["phisical_adress"] ?? null;
        $config["passport_number"] = $lawParams["passport_number"] ?? $config["passport_number"] ?? null;
        $config["passport_date"] = $lawParams["passport_date"] ?? $config["passport_date"] ?? null;
        $config["agreement"] = $lawParams["agreement"] ?? $config["agreement"] ?? false;
        $config["offer_link"] = $lawParams["offer_link"] ?? $config["offer_link"] ?? null;

        $company->law_params = $config;

        $company->save();

        return new CompanyResource($company->refresh());
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function editCompany(array $data, $companyLogo = null): CompanyResource
    {
        if (is_null($this->botUser))
            throw new HttpException(400, "Менеджер не указан!");

        $validator = Validator::make($data, [
            'id' => "required",
            'title' => "required|string:255",
            //   'slug' => "required|string:190",
            'description' => "required|string:1000",
            // 'address' => "required|string:255",
            //   'email' => "required|string:255",
            // 'vat_code' => "required|integer",
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
        // $tmp->creator_id = $this->botUser->id;
        //$tmp->owner_id = $this->botUser->id;


        $company = Company::query()->where("id", $data["id"])
            ->first();

        if (is_null($company))
            throw new HttpException(404, "Компания (клиент) не найдена!");

        $company->update((array)$tmp);

        return new CompanyResource($company->refresh());
    }

    public function locationsList($companyId = null, $size = null): LocationCollection
    {
        $size = $size ?? config('app.results_per_page');

        $locations = Location::query();

        if (is_null($companyId))
            $locations = $locations
                ->with(["company"]);

        if (!is_null($companyId))
            $locations = $locations
                ->where("company_id", $companyId);

        $locations = $locations
            ->orderBy("created_at", "DESC")
            ->paginate($size);

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

        $locationId = $tmp->id ?? null;
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
