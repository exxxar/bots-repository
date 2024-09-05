<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BotPageCollection;
use App\Http\Resources\BotPageResource;
use App\Http\Resources\ProductCollectionCollection;
use App\Http\Resources\ProductCollectionResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotPage;
use App\Models\Iiko;
use App\Models\ProductCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductCollectionLogicFactory
{
    use LogicUtilities;

    protected $bot;
    protected $botUser;
    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;


    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }


    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function store(array $data, $uploadedPhoto = null): ProductCollectionResource
    {
        if (is_null($this->bot)||is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            'title'=> "required",
            'description'=> "required",
            'is_public'=>"",
            'is_active'=>"",
            'discount'=>"",
            'order_position'=>"",
            'config'=>"",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        if (!is_null($uploadedPhoto))
            $imageName = $this->uploadPhoto("/public/companies/" . $this->bot->company->slug, $uploadedPhoto);

        $id = $data["id"] ?? null;

        $tmp = [
            'bot_id' => $this->bot->id,
            'owner_id' => $this->botUser->id,
            'title' => $data["title"] ?? null,
            'image' => $imageName ?? null,
            'description' => $data["description"] ?? null,
            'is_public' => (($data["is_public"] ?? false) == "true"),
            'is_active' => (($data["is_active"] ?? false) == "true"),
            'discount' => $data["discount"] ?? 0,
            'order_position' => $data["order_position"] ?? 0,
            'config' => isset($data["config"]) ? json_decode($data["config"]) : null
        ];



        $collection = ProductCollection::query()
            ->where("id", $id)
            ->first();

        if (is_null($collection))
            $collection = ProductCollection::query()
                ->create($tmp);
        else
            $collection->update($tmp);

        if (!is_null($data["products"]??null)){

            $ids = array_values(Collection::make(json_decode($data["products"]))
                ->pluck("id")->toArray());

            $collection->products()->sync($ids);
        }

        return new ProductCollectionResource($collection);
    }



    /**
     * @throws HttpException
     */
    public function list($search = null, $size = null,$order = "updated_at", $direction = "desc", $global = false)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $collections = ProductCollection::query()
            ->with(["products.productCategories"])
            ->where("bot_id", $this->bot->id);

        if ($global)
            $collections = $collections
                ->where("is_public", true)
                ->where("is_active", true);
               // ->whereHas("products");

        if (!is_null($search))
            $collections = $collections
                ->where(function ($q) use ($search) {
                        $q->where("title", 'like', "%$search%")
                        ->orwhere("description", 'like', "%$search%");

                })
                ->orWhere("id", 'like', "%$search%");

        $collections = $collections->orderBy($order, $direction);

        return ProductCollectionResource::collection($collections->paginate($size));
    }

    /**
     * @throws HttpException
     */
    public function duplicate($id): ProductCollectionResource
    {
        if (is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $collection = ProductCollection::query()
            ->with(["product"])
            ->where("id", $id)
            ->first();

        if (is_null($collection))
            throw new HttpException(404, "Коллекция не найдена!");

        $new = $collection->replicate();

        $new->owner_id = $this->botUser->id;
        $new->save();


        return new ProductCollectionResource($new);
    }

    /**
     * @throws HttpException
     */
    public function destroy($pageId, $force = false): ProductCollectionResource
    {

        $collection = ProductCollection::query()->where("id", $pageId)
                ->first();


        if (is_null($collection))
            throw new HttpException(404, "Коллекция не найдена!");

        $tmp = $collection;
        $collection->products()->detach();
        $collection->delete();

        return new ProductCollectionResource($tmp);
    }
}
