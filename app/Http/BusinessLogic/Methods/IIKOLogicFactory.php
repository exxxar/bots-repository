<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\IikoResource;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\Iiko;
use App\Models\Product;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class IIKOLogicFactory
{
    protected $bot;

    public function __construct()
    {
        $this->bot = null;

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
    public function getToken($apiLogin = null): mixed
    {

        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $iiko = $this->bot->iiko ?? null;

        if (is_null($iiko)) {
            $iiko = Iiko::query()
                ->create([
                    'bot_id' => $this->bot->id,
                    'api_login' => $apiLogin,
                    'organization_id' => null,
                    'terminal_group_id' => null,
                ]);
        }

        if (!is_null($iiko) && !is_null($apiLogin)) {
            $iiko->api_login = $apiLogin;
            $iiko->save();
        }

        if (is_null($iiko->api_login ?? null))
            throw new HttpException(400, "API логин не задан");

        $url = config('iiko.api_url');

        $result = Http::post("$url/1/access_token", [
            'apiLogin' => $iiko->api_login,
        ]);

        if ($result->status() != 200)
            throw new HttpException($result->status(), $result->json("errorDescription"));

        return $result->json("token") ?? null;

    }

    /**
     * @throws HttpException
     */
    public function get(): IikoResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $iiko = $this->bot->iiko ?? null;

        if (is_null($iiko))
            $iiko = Iiko::query()
                ->create([
                    'bot_id' => $this->bot->id,
                    'api_login' => null,
                    'organization_id' => null,
                    'terminal_group_id' => null,
                ]);


        return new IikoResource($iiko);
    }

    /**
     * @throws HttpException
     */
    public function organizations($token): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $url = config('iiko.api_url');

        $result = Http::asJson()
            ->withToken(trim($token))
            ->post("$url/1/organizations", [
                'organizationIds' => [],
                'returnAdditionalInfo' => true,
                'includeDisabled' => true,
                'returnExternalData' => ["string"],
            ]);


        return $result->json("organizations") ?? null;
    }

    /**
     * @throws HttpException
     */
    public function terminals($token, $organizationId): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $url = config('iiko.api_url');

        $result = Http::asJson()
            ->withToken(trim($token))
            ->post("$url/1/terminal_groups", [
                'organizationIds' => [$organizationId],
                'includeDisabled' => true,
                'returnExternalData' => ["string"],
            ]);

        return $result->json("terminalGroups") ?? null;
    }

    /**
     * @throws HttpException
     */
    public function menus(): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $url = config('iiko.api_url');

        $token = $this->getToken();

        $organizationId = $this->bot->iiko->organization_id ?? null;

        if (is_null($organizationId))
            throw new HttpException(404, "Организация не найдена!");

        $result = Http::asJson()
            ->withToken(trim($token))
            ->post("$url/2/menu", [
                'organizationId' => $organizationId,
                'startRevision' => 0,

            ]);

        return $result->json("externalMenus") ?? null;
    }

    /**
     * @throws HttpException
     */
    public function products($menuId): mixed
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $url = config('iiko.api_url');

        $token = $this->getToken();

        $organizationId = $this->bot->iiko->organization_id ?? null;

        if (is_null($organizationId))
            throw new HttpException(404, "Организация не найдена!");

        $result = Http::asJson()
            ->withToken(trim($token))
            ->post("$url/2/menu/by_id", [
                'organizationIds' => [$organizationId],
                'externalMenuId' => $menuId,
            ]);


        return $result->json() ?? null;
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function store(array $data): IikoResource
    {

        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            "api_login" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $iiko = Iiko::query()
            ->where("api_login", $data["api_login"])
            ->where("bot_id", $this->bot->id)
            ->first();

        $tmp = [
            'bot_id' => $this->bot->id,
            'api_login' => $data["api_login"] ?? null,
            'organization_id' => $data["organization_id"] ?? null,
            'terminal_group_id' => $data["terminal_group_id"] ?? null,
        ];

        if (is_null($iiko))
            $iiko = Iiko::query()
                ->create($tmp);
        else
            $iiko->update($tmp);

        return new IikoResource($iiko);
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function storeProductsAndCategories(array $data): void
    {

        if (is_null($this->bot))
            throw new HttpException(400, "Условия функции не выполнены!");

        $validator = Validator::make($data, [
            "products" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        foreach ($products as $product) {
            $product->in_stop_list_at = Carbon::now();
            $product->deleted_at = Carbon::now();
            $product->save();
        }

        foreach ($data["products"] as $product) {
            $product = (object)$product;

            $tmpProduct = Product::query()
                ->where("bot_id", $this->bot->id)
                ->where("iiko_article", $product->id)
                ->first();

            $tmp = [
                'article' => $product->sku ?? null,
                'vk_product_id' => null,
                'frontpad_article' => null,
                'iiko_article' => $product->id ?? null,
                'title' => $product->name ?? null,
                'description' => $product->description ?? null,
                'images' => [$product->image ?? null],
                'type' => 0,
                'old_price' => 0,
                'current_price' => $product->price ?? 0,
                'variants',
                'in_stop_list_at' => $product->in_stop ? Carbon::now() : null,
                'bot_id' => $this->bot->id,
                'deleted_at' => null
            ];

            if (is_null($tmpProduct))
                $tmpProduct = Product::query()
                    ->create($tmp);
            else
                $tmpProduct->update($tmp);

            $category = $product->category;

            $tmpProductCategory = ProductCategory::query()
                ->where("title", $category)
                ->where("bot_id", $this->bot->id)
                ->first();

            if (is_null($tmpProductCategory))
                $tmpProductCategory = ProductCategory::query()
                    ->create([
                        'title' => $category,
                        'bot_id' => $this->bot->id,
                    ]);

            $tmpProduct->productCategories()->attach([$tmpProductCategory->id]);
        }


    }
}
