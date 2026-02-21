<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Exports\ProductExport;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\Transaction;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class ProductLogicFactory extends BaseLogicFactory
{
    use LogicUtilities;


    public function byIds(array $ids = []): ProductCollection
    {
        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();

        return new ProductCollection($products);
    }

    public function loadMoreProductsByCategories($categoryId, $offset, $partnerId = null)
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $botId = $partnerId ?? $this->bot->id;

        $category = ProductCategory::query()
            ->where("bot_id", $botId)
            ->where("is_active", true)
            ->withCount('products')
            ->has("products", ">", 0)
            ->orderBy("order_position", "ASC")
            ->where("id", $categoryId)
            ->first();

        if (is_null($category))
            return [];


        $category->setRelation(
            'products',
            $category->products()
                ->whereNull("in_stop_list_at")
                ->where("bot_id", $botId)
                ->take(8)
                ->offset($offset)
                ->get()
        );


        return $category->products ?? [];
    }

    /**
     * @throws HttpException
     */
    public function listByCategories(array $data = null)
    {

        if (is_null($this->bot)) {
            throw new HttpException(404, "Бот не найден!");
        }

        $botId = $data["partner_id"] ?? $this->bot->id;

        $categories = ProductCategory::query()
            ->where("bot_id", $botId)
            ->where("is_active", true)
            ->withCount(['products' => function ($q) {
                $q->whereNull('deleted_at')
                    ->whereNull("in_stop_list_at");
            }])
            ->with(['products' => function ($q) use ($botId) {
                $q->where("bot_id", $botId)
                    ->whereNull("deleted_at")
                    ->whereNull("in_stop_list_at")
                    ->take(8);
            }])
            ->has("products", ">", 0)
            ->orderBy("order_position", "ASC")
            ->get();

        $withoutCategory = Product::query()
            ->where("bot_id", $botId)
            ->doesntHave("productCategories")
            ->whereNull("in_stop_list_at")
            ->whereNull("deleted_at")
            ->take(8)
            ->get();


        return (object)[
            "data" => [
                [
                    "id" => -1,
                    "is_active" => true,
                    "order_position" => 0,
                    "title" => "Без категории",
                    "bot_id" => $botId,
                    "products" => $withoutCategory->toArray(),
                    "products_count" => $withoutCategory->count()
                ],
                ...$categories->toArray()
            ]
        ];



    }

    public function favList(): ProductCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Параметры функции не заданы!");

        $favIds = $this->botUser->config["favorites"] ?? [];

        $products = Product::query()
            ->withTrashed()
            ->whereIn("id", $favIds)
            ->get();

        return new ProductCollection($products);

    }

    /**
     * @throws HttpException
     */
    public function list($search = null, array $filters = null, $size = null, $needAll = false, $needRemoved = false): ProductCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        //need_hide_disabled_products
        $products = Product::query();

        if ($needRemoved)
            $products = $products->withTrashed();

        $products = $products->with(["productCategories", "productOptions"])
            ->where("bot_id", $this->bot->id);


        $products = $needAll ? $products->whereNull("in_stop_list_at") :
            $products->whereNotNull("in_stop_list_at");


        if (!is_null($search))
            $products = $products
                ->where(function ($q) use ($search) {
                    $q->where("title", "like", "%$search%");
                    // ->orWhere("description", "like", "%$search%");
                });

        if (!empty($filters["categories"])) {
            $products = $products
                ->whereRelation('productCategories', function ($q) use ($filters) {
                    $q->whereIn('id', $filters["categories"]);
                });
        }

        if (($filters["min_price"] ?? 0) > 0 && ($filters["max_price"] ?? 0) > 0) {
            $products = $products->where(function ($q) use ($filters) {
                $q->where("current_price", ">=", $filters["min_price"])
                    ->where("current_price", "<=", $filters["max_price"]);
            });
        }

        if (($filters["min_price"] ?? 0) > 0 && ($filters["max_price"] ?? 0) == 0) {
            $products = $products->where("current_price", ">=", $filters["min_price"]);
        }

        if (($filters["min_price"] ?? 0) == 0 && ($filters["max_price"] ?? 0) > 0) {
            $products = $products->where("current_price", "<=", $filters["max_price"]);
        }

        $products = $products
            ->orderBy("created_at", "DESC")
            ->paginate($size);

        return new ProductCollection($products);
    }

    /**
     * @throws HttpException
     */
    public function categories($isFull = false, array $data = [], $size = null): ProductCategoryCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $order = $data["order_by"] ?? "updated_at";
        $direction = $data["direction"] ?? "desc";


        $categories = ProductCategory::query()
            ->where("bot_id", $this->bot->id);

        if (!$isFull)
            $categories = $categories
                ->where("is_active", true);

        $categories = $categories
            ->orderBy($order, $direction)
            ->paginate($size);

        return new ProductCategoryCollection($categories);
    }

    /**
     * @throws HttpException
     */
    public function product($productId): ProductResource
    {

        $product = Product::query()
            ->where("id", $productId)
            ->first();

        return is_null($product) ?
            throw new HttpException(404, "Продукт не найден!") :
            new ProductResource($product);

    }

    /**
     * @throws HttpException
     */
    public function randomList($take = 10): ProductCollection
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        return new ProductCollection($products->count() > 10 ? $products->random($take) : $products);

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function loadRecommendedProducts(): ProductCollection
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        $recommendation = $this->bot->settings["recommendation"] ?? [
            "categories" => [],
            "products" => [],
            "excludes" => []
        ];

        $categoryIds = $recommendation["categories"] ?? [];

        $productCategories = ProductCategory::query()
            ->with(["products"])
            ->whereIn("id", $categoryIds)
            ->get();

        $tmpProducts = [];

        foreach ($productCategories as $category)
            foreach ($category->products as $product)
                $tmpProducts[] = $product->id;

        $productIds = $recommendation["products"] ?? [];

        $excludeIds = $recommendation["excludes"] ?? [];

        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->whereIn("id", [...$productIds, ...$tmpProducts])
            ->whereNotIn("id", $excludeIds)
            ->get();

        return new ProductCollection($products);

    }


    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function changeCategoryRecommendationStatus(array $data): array
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "category_id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $categoryId = $data["category_id"];
        $status = $data["status"] ?? 0;

        $recommendation = $this->bot->settings["recommendation"] ?? [
            "categories" => [],
            "products" => [],
            "excludes" => []
        ];

        switch ($status) {
            default:
            case 0:
                $recommendation["categories"] = array_filter($recommendation["categories"] ?? [], fn($v) => $v !== $categoryId);
                break;
            case 1:
                $recommendation["categories"][] = $categoryId;
                break;

        }


        $config = $this->bot->settings;
        $config["recommendation"] = $recommendation;
        $this->bot->config = $config;
        $this->bot->save();

        return $config["recommendation"];

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function changeRecommendationStatus(array $data): array
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "product_id" => "required",
            "status" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $productId = $data["product_id"];
        $status = $data["status"] ?? 0;

        $recommendation = $this->bot->settings["recommendation"] ?? [
            "categories" => [],
            "products" => [],
            "excludes" => []
        ];
        switch ($status) {
            default:
            case 0:
                $recommendation["products"] = array_filter($recommendation["products"] ?? [], fn($v) => $v !== $productId);
                $recommendation["excludes"] = array_filter($recommendation["excludes"] ?? [], fn($v) => $v !== $productId);

                break;
            case 1:
                $recommendation["products"][] = $productId;
                $recommendation["excludes"] = array_filter($recommendation["excludes"] ?? [], fn($v) => $v !== $productId);
                break;
            case 2:
                $recommendation["excludes"][] = $productId;
                $recommendation["products"] = array_filter($recommendation["products"] ?? [], fn($v) => $v !== $productId);
                break;
        }

        $config = $this->bot->settings;
        $config["recommendation"] = $recommendation;
        $this->bot->config = $config;
        $this->bot->save();

        return $config["recommendation"];
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createOrUpdate(array $data, array $uploadedPhotos = null): ProductResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "article" => "",
            "title" => "required",
            "type" => "required",
            "current_price" => "required",
            "in_stop_list_at" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $slug = $this->bot->company->slug;


        $photos = !is_null($uploadedPhotos) ?
            $this->uploadPhotos("/public/companies/$slug", $uploadedPhotos) : [];

        if (count($photos) > 0)
            for ($i = 0; $i < count($photos); $i++) {
                $photos[$i] = "/images-by-bot-id/" . $this->bot->id . "/" . $photos[$i];
            }


        $images = $data["images"] ?? null;

        if (!is_null($images))
            $images = json_decode($images);

        $images = count($photos) == 0 ? (is_array($images) ? $images : null) : [...$photos, ...$images];

        $variants = $data["variants"] ?? null;

        if (!is_null($variants))
            $variants = json_decode($variants);

        $removedOptions = $data["removed_options"] ?? null;

        if (!is_null($removedOptions)) {
            $removedOptions = json_decode($removedOptions);
            foreach ($removedOptions as $key => $id) {
                $tmpOption = ProductOption::query()
                    ->where("id", $id)
                    ->first();

                if (!is_null($tmpOption)) {
                    $tmpOption->delete();
                }

            }
        }

        $productId = $data["id"] ?? null;

        $tmp = [
            'article' => $data["article"] ?? null,
            'vk_product_id' => $data["vk_product_id"] ?? null,
            'title' => $data["title"] ?? null,
            'description' => $data["description"] ?? null,
            'delivery_terms' => $data["delivery_terms"] ?? null,
            'images' => $images,
            'type' => $data["type"] ?? 0,
            'old_price' => $data["old_price"] ?? 0,
            'current_price' => $data["current_price"] ?? 0,
            'variants' => $variants,

            'not_for_delivery' => ($data["not_for_delivery"] ?? false) == "true",//? Carbon::now() : false,
            'is_weight_product' => ($data["is_weight_product"] ?? false) == "true",
            'bot_id' => $data["bot_id"] ?? $this->bot->id,
            'weight_config' => is_null($data["weight_config"] ?? null) ?
                null : json_decode($data["weight_config"] ?? '[]'),
            'dimension' => is_null($data["dimension"] ?? null) ?
                null : json_decode($data["dimension"] ?? '[]'),
        ];


        if (!is_null($productId)) {
            $product = Product::query()
                ->with(["productCategories", "productOptions"])
                ->where("id", $productId)
                ->first();

            $product->update($tmp);
        } else
            $product = Product::query()
                ->with(["productCategories", "productOptions"])
                ->create($tmp);


        if (!is_null($data["in_stop_list_at"] ?? null)) {
            $product->in_stop_list_at = $data["in_stop_list_at"] == "true" ? Carbon::now() : null;
            $product->save();
        }


        $options = $data["options"] ?? null;

        if (!is_null($options)) {
            $options = json_decode($options);
            //dd($options);
            foreach ($options as $option) {
                $option = (object)$option;
                $key = Str::uuid();

                if (is_null($option->id))
                    ProductOption::query()
                        ->create([
                            'key' => $key,
                            'title' => $option->title,
                            'value' => $option->value,
                            'section' => $option->section,
                            'product_id' => $product->id
                        ]);
                else
                    ProductOption::query()
                        ->find($option->id)
                        ->update([
                            'title' => $option->title,
                            'value' => $option->value,
                            'section' => $option->section,
                        ]);
            }

        }

        $categories = $data["categories"] ?? null;

        if (!is_null($categories)) {

            $tmp = [];
            $categories = json_decode($categories);


            foreach ($categories as $category) {
                $tmpCategory = ProductCategory::query()
                    ->where("id", $category)
                    ->first();

                if (is_null($tmpCategory))
                    $tmpCategory = ProductCategory::query()
                        ->create([
                            'title' => $category,
                            'bot_id' => $this->bot->id
                        ]);


                $tmp[] = $tmpCategory->id;
            }
            $product->productCategories()->sync($tmp);


        }

        return new ProductResource($product);

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createOrUpdateCategory(array $data): ProductCategoryResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "category" => "required",
        ]);


        if ($validator->fails())
            throw new ValidationException($validator);

        $id = $data["category"]["id"] ?? null;
        $tmp = [
            'title' => $data["category"]["title"] ?? '-',
            'order_position' => $data["category"]["order_position"] ?? 0,
            'bot_id' => $this->bot->id,
        ];

        if (is_null($id))
            $category = ProductCategory::query()
                ->create($tmp);
        else {
            $category = ProductCategory::query()->find($id);

            $category->update($tmp);
        }

        return new ProductCategoryResource($category);
    }

    /**
     * @throws HttpException
     */
    public function stopList($productId): ProductResource
    {
        $product = Product::query()
            ->withTrashed()
            ->find($productId);

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден");


        if (is_null($product->in_stop_list_at))
            $product->in_stop_list_at = Carbon::now();
        else
            $product->in_stop_list_at = null;

        $product->save();

        return new ProductResource($product);
    }

    /**
     * @throws HttpException
     */
    public function restore($productId): ProductResource
    {
        $product = Product::query()
            ->withTrashed()
            ->find($productId);

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден");


        $product->deleted_at = null;
        $product->save();

        return new ProductResource($product);
    }

    /**
     * @throws HttpException
     */
    public function destroy($productId): ProductResource
    {
        $product = Product::query()
            ->with(["productCategories", "productOptions"])
            ->find($productId);

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден");

        $options = $product->productOptions;

        if (!empty($options))
            foreach ($options as $option)
                $option->delete();

        $categories = $product->productCategories;

        $tmp = [];
        if (!empty($categories))
            foreach ($categories as $category)
                $tmp[] = $category->id;

        $tmpProduct = $product;
        $product->productCategories()->detach($tmp);

        $product->delete();

        Log::info("запустилось удаление продуктов!");
        return new ProductResource($tmpProduct);
    }

    /**
     * @throws HttpException
     */
    public function removeCategory($categoryId): ProductCategoryResource
    {
        $category = ProductCategory::query()
            ->with(["products"])
            ->where("id", $categoryId)
            ->first();

        if (is_null($category))
            throw new HttpException(404, "Категория не найдена");


        $ids = $category->products
            ->get()
            ->pluck("id");

        $category->products->detach(array_values($ids));

        $tmpCategory = $category;
        $category->delete();

        return new ProductCategoryResource($tmpCategory);
    }

    /**
     * @throws HttpException
     */
    public function changeCategoryStatus($categoryId): ProductCategoryResource
    {
        $category = ProductCategory::query()
            ->with(["products"])
            ->find($categoryId);

        if (is_null($category))
            throw new HttpException(404, "Категория не найдена");

        $category->is_active = !$category->is_active ?? false;
        $category->save();

        return new ProductCategoryResource($category);
    }


    public function exportAllProducts($data = null)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Условия функции не выполнены!");

        $name = Str::uuid();

        $date = Carbon::now()->format("Y-m-d H-i-s");

        Excel::store(new ProductExport($this->bot->id), "$name.xls", "public", \Maatwebsite\Excel\Excel::XLS);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument($this->botUser->telegram_chat_id,
                "Экспорт товаров",
                InputFile::create(
                    storage_path("app/public") . "/$name.xls",
                    "products-export-$date.xls"
                )
            );

        unlink(storage_path("app/public") . "/$name.xls");

    }

    /**
     * @throws HttpException
     */
    public function removeAllProducts(): void
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $products = Product::query()
            ->with(["productCategories", "productOptions"])
            ->where("bot_id", $this->bot->id)
            ->get();

        if (empty($products))
            throw new HttpException(404, "Продукты не найден");

        $baskets = Basket::query()
            ->where("bot_id", $this->bot->id)
            ->get();

        if (!empty($baskets))
            foreach ($baskets as $basket)
                $basket->delete();

        foreach ($products as $product) {
            $options = $product->productOptions;

            if (!empty($options))
                foreach ($options as $option)
                    $option->delete();

            $categories = $product->productCategories;

            $tmp = [];
            if (!empty($categories))
                foreach ($categories as $category)
                    $tmp[] = $category->id;

            $product->productCategories()->detach($tmp);

            $product->delete();
        }

        Log::info("запустилось удаление ВСЕХ продуктов!");
    }

    /**
     * @throws HttpException
     */
    public function destroyCategory($categoryId): ProductCategoryResource
    {
        $category = ProductCategory::query()
            ->find($categoryId);

        if (is_null($category))
            throw new HttpException(404, "Категория не найден");

        $tmpCategory = $category;
        $category->delete();

        return new ProductCategoryResource($tmpCategory);
    }

    /**
     * @throws HttpException
     */
    public function duplicate($productId): ProductResource
    {
        $product = Product::query()
            ->with(["productCategories", "productOptions"])
            ->find($productId);

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден");

        $newProduct = $product->replicate();
        $newProduct->save();

        if (!empty($product->productCategories)) {
            $tmp = [];
            foreach ($product->productCategories as $category)
                $tmp[] = $category->id;

            $newProduct->productCategories()->sync($tmp);
        }

        if (!empty($product->productOptions))
            foreach ($product->productOptions as $option)
                ProductOption::query()
                    ->create([
                        'key' => Str::uuid(),
                        'title' => $option->title,
                        'value' => $option->value,
                        'section' => $option->section,
                        'product_id' => $newProduct->id
                    ]);


        return new ProductResource($newProduct);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function createCheckoutLink(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Требования функции не выполнены!");

        $validator = Validator::make($data, [
            "products" => "required",
            "name" => "required",
            "phone" => "required",
            //  "address" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmpProducts = json_decode($data["products"]);
        $ids = Collection::make($tmpProducts)
            ->pluck("id");

        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();

        $needPickup = ($data["need_pickup"] ?? "false") == "true";
        $hasDisability = ($data["has_disability"] ?? "false") == "true";
        $useCashback = ($data["use_cashback"] ?? "false") == "true";
        $needPaymentLink = ($data["need_payment_link"] ?? "false") == "true";
        $cash = ($data["cash"] ?? "false") == "true";
        $promo = isset($data["promo"]) ? json_decode($data["promo"]) : null;

        $message = (!$needPickup ? "#заказдоставка\n\n" : "#заказсамовывоз\n\n");

        $persons = $data["persons"] ?? 1;
        $time = $data["time"] ?? null;
        $whenReady = ($data["when_ready"] ?? "false") == "true";

        $summaryPrice = 0;
        $summaryCount = 0;

        $disabilities = json_decode($data["disabilities"] ?? '[]');
        $allergy = $data["allergy"] ?? 'не указана';

        if ($hasDisability) {

            $disabilitiesText = "<b>Внимание!</b> у клиента присутствуют ограничения по здоровью!\n";

            foreach ($disabilities as $disability)
                $disabilitiesText .= $disability == "пищевая аллергия" ? "-<em>$disability на: $allergy</em>\n" : "-<em>$disability</em>\n";


            $message .= $disabilitiesText . "\n";
        }

        $tmpOrderProductInfo = [];
        foreach ($products as $product) {

            $tmpCount = array_values(array_filter($tmpProducts, function ($item) use ($product) {
                return $item->id === $product->id;
            }))[0]->count ?? 0;


            $tmpPrice = ($product->current_price ?? 0) * $tmpCount;
            $message .= sprintf("%s x%s=%s руб.\n",
                $product->title,
                $tmpCount,
                $tmpPrice
            );

            $tmpOrderProductInfo[] = (object)[
                "title" => $product->title,
                "count" => $tmpCount,
                "price" => $tmpPrice,
                'frontpad_article' => $product->frontpad_article ?? null,
                'iiko_article' => $product->iiko_article ?? null,
            ];

            $summaryCount += $tmpCount;
            $summaryPrice += $tmpPrice;
        }

        if (is_null($promo))
            $promo = (object)[
                "activate_price" => 0,
                "discount" => 0,
                "code" => "не указан"
            ];

        $maxUserCashback = $this->botUser->cashback->amount ?? 0;
        $deliveryPrice = $data["delivery_price"] ?? 0;
        $distance = $data["distance"] ?? 0;
        $botCashbackPercent = $this->bot->max_cashback_use_percent ?? 0;
        $cashBackAmount = ($summaryPrice * ($botCashbackPercent / 100));
        $discount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0) + ($promo->discount ?? 0);

        $deliveryNote = ($data["info"] ?? 'Не указано') . "\n"
            . "Номер подъезда: " . ($data["entrance_number"] ?? 'Не указан') . "\n"
            . "Номер этажа: " . ($data["floor_number"] ?? 'Не указан') . "\n"
            . "Тип оплаты: " . ($cash ? "Наличкой" : "Картой") . "\n"
            . "Сдача с:" . ($data["money"] ?? 'Не указано') . "\n"
            . "Время доставки:" . ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')) . "\n"
            . "Число персон:" . $persons . "\n"
            . "Ограничения пользователя:\n" . ($disabilitiesText ?? 'не указаны');

        $address = (($data["city"] ?? "") . "," . ($data["street"] ?? "") . "," . ($data["building"] ?? ""));

        $this->contactsPrepare($data);


        $order = Order::query()->create([
            'bot_id' => $this->bot->id,
            'deliveryman_id' => null,
            'customer_id' => $this->botUser->id,
            'delivery_service_info' => null,//информация о сервисе доставки
            'deliveryman_info' => null,//информация о доставщике
            'product_details' => [
                (object)[
                    "from" => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
                    "products" => $tmpOrderProductInfo
                ]
            ],//информация о продуктах и заведении, из которого сделан заказ
            'product_count' => $summaryCount,
            'summary_price' => max(1, $summaryPrice - $discount),
            'delivery_price' => $deliveryPrice,
            'delivery_range' => $distance ?? 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => $deliveryNote,
            'receiver_name' => $data["name"] ?? 'Нет имени',
            'receiver_phone' => $data["phone"] ?? 'Нет телефона',

            'address' => $address . "," . ($data["flat_number"] ?? ""),
            'receiver_latitude' => $geo->latitude ?? 0,
            'receiver_longitude' => $geo->longitude ?? 0,

            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => null,
        ]);

        return BusinessLogic::payment()
            ->setBot($this->bot)
            ->setBotUser($this->botUser)
            ->setSlug($this->slug)
            ->checkoutLink([
                "discount" => $discount,
                "products" => $tmpProducts
            ]);


    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function checkoutInformation(array $data, $uploadedPhoto = null): void
    {

        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Требования функции не выполнены!");


        $validator = Validator::make($data, [
            "products" => "required",
            "name" => "required",
            "phone" => "required",
            //  "address" => "",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmpProducts = json_decode($data["products"]);
        $tmpCollections = json_decode($data["collections"]);

        $ids = Collection::make($tmpProducts)
            ->pluck("id");


        $usePaymentSystem = (Collection::make($this->slug->config)
            ->where("key", "use_payment_system")
            ->first())["value"] ?? false;

        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();


        $needPickup = ($data["need_pickup"] ?? "false") == "true";
        $hasDisability = ($data["has_disability"] ?? "false") == "true";
        $useCashback = ($data["use_cashback"] ?? "false") == "true";
        $needPaymentLink = ($data["need_payment_link"] ?? "false") == "true";

        $paymentTypes = ["Онлайн в боте", "Картой в заведении", "Переводом", "Наличными"];
        $cash = $paymentTypes[$data["payment_type"] ?? 0];


        $message = (!$needPickup ? "#заказдоставка\n\n" : "#заказсамовывоз\n\n");
        $deliveryPrice = $data["delivery_price"] ?? 0;
        $distance = $data["distance"] ?? 0;
        $persons = $data["persons"] ?? 1;
        $time = $data["time"] ?? null;
        $whenReady = ($data["when_ready"] ?? "false") == "true";

        $summaryPrice = 0;
        $summaryCount = 0;

        $disabilities = json_decode($data["disabilities"] ?? '[]');
        $allergy = $data["allergy"] ?? 'не указана';
        $promo = isset($data["promo"]) ? json_decode($data["promo"]) : null;

        if ($hasDisability) {

            $disabilitiesText = "<b>Внимание!</b> у клиента присутствуют ограничения по здоровью!\n";

            foreach ($disabilities as $disability)
                $disabilitiesText .= $disability == "пищевая аллергия" ? "-<em>$disability на: $allergy</em>\n" : "-<em>$disability</em>\n";

            $message .= $disabilitiesText . "\n";


        }

        $tmpOrderProductInfo = [];
        foreach ($products as $product) {
            $tmpCount = array_values(array_filter($tmpProducts, function ($item) use ($product) {
                return $item->id === $product->id;
            }))[0]->count ?? 0;

            $tmpPrice = ($product->current_price ?? 0) * $tmpCount;
            $message .= sprintf("💎%s x%s=%s руб.\n",
                $product->title,
                $tmpCount,
                $tmpPrice
            );

            $tmpOrderProductInfo[] = (object)[
                "title" => $product->title,
                "count" => $tmpCount,
                "price" => $tmpPrice,
                'frontpad_article' => $product->frontpad_article ?? null,
                'iiko_article' => $product->iiko_article ?? null,
            ];

            $summaryCount += $tmpCount;
            $summaryPrice += $tmpPrice;
        }

        $message .= "\n";
        foreach ($tmpCollections as $collection) {

            $collection = (object)$collection;
            $collectionTitles = "";
            $tmpPrice = $collection->data->current_price ?? 0;
            $tmpCount = $collection->count ?? 0;

            foreach (($collection->data->products ?? []) as $product) {
                if ($product->is_checked) {
                    $collectionTitles .= "-" . $product->title . "\n";

                    $tmpOrderProductInfo[] = (object)[
                        "title" => "Коллекция `" . ($collection->data->title) . "`: " . $product->title,
                        "count" => 1,
                        "price" => 0,
                        'frontpad_article' => $product->frontpad_article ?? null,
                        'iiko_article' => $product->iiko_article ?? null,
                    ];
                }
            }

            $tmpPrice = $tmpPrice * $tmpCount;
            $message .= sprintf("💎Коллекция `%s` x%s=%s руб.:\n%s\n",
                ($collection->data->title),
                $tmpCount,
                $tmpPrice,
                $collectionTitles,
            );

            $summaryCount += $tmpCount;
            $summaryPrice += $tmpPrice;
        }

        $maxUserCashback = $this->botUser->cashback->amount ?? 0;
        $botCashbackPercent = $this->bot->max_cashback_use_percent ?? 0;
        $cashBackAmount = ($summaryPrice * ($botCashbackPercent / 100));

        if (is_null($promo))
            $promo = (object)[
                "activate_price" => 0,
                "discount" => 0,
                "code" => "не указан"
            ];


        $discount = ($useCashback ? min($cashBackAmount, $maxUserCashback) : 0) +
            ($summaryPrice >= ($promo->activate_price ?? 0) ? ($promo->discount ?? 0) : 0);

        $deliveryNote = ($data["info"] ?? 'Не указано') . "\n"
            . "Номер подъезда: " . ($data["entrance_number"] ?? 'Не указан') . "\n"
            . "Номер этажа: " . ($data["floor_number"] ?? 'Не указан') . "\n"
            . "Тип оплаты: " . $cash . "\n"
            . "Сдача с:" . ($data["money"] ?? 'Не указано') . "\n"
            . "Время доставки:" . ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')) . "\n"
            . "Число персон:" . $persons . "\n"
            . "Ограничения пользователя:\n" . ($disabilitiesText ?? 'не указаны');

        $address = (($data["city"] ?? "") . "," . ($data["street"] ?? "") . "," . ($data["building"] ?? ""));

        $this->botUser->city = $data["city"] ?? $this->botUser->city ?? null;
        $this->botUser->address = ($data["street"] ?? "") . "," . ($data["building"] ?? "");
        $this->botUser->save();

        //сделать чек на оплату (pdf)
        $order = Order::query()->create([
            'bot_id' => $this->bot->id,
            'deliveryman_id' => null,
            'customer_id' => $this->botUser->id,
            'delivery_service_info' => null,//информация о сервисе доставки
            'deliveryman_info' => null,//информация о доставщике
            'product_details' => [
                (object)[
                    "from" => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
                    "products" => $tmpOrderProductInfo
                ]
            ],//информация о продуктах и заведении, из которого сделан заказ
            'product_count' => $summaryCount,
            'summary_price' => $summaryPrice,
            'delivery_price' => $deliveryPrice,
            'delivery_range' => $distance ?? 0,
            'deliveryman_latitude' => 0,
            'deliveryman_longitude' => 0,
            'delivery_note' => $deliveryNote,
            'receiver_name' => $data["name"] ?? 'Нет имени',
            'receiver_phone' => $data["phone"] ?? 'Нет телефона',

            'address' => $address . "," . ($data["flat_number"] ?? ""),
            'receiver_latitude' => $geo->latitude ?? 0,
            'receiver_longitude' => $geo->longitude ?? 0,

            'status' => OrderStatusEnum::NewOrder->value,//новый заказ, взят доставщиком, доставлен, не доставлен, отменен
            'order_type' => OrderTypeEnum::InternalStore->value,//тип заказа: на продукт из магазина, на продукт конструктора
            'payed_at' => Carbon::now(),
        ]);

        BusinessLogic::review()
            ->setBotUser($this->botUser)
            ->setBot($this->bot)
            ->prepareReviews($order->id, $ids);

        $message .= "Итого: $summaryPrice руб. за $summaryCount ед. " . ($discount > 0 ? "Скидка: $discount руб." : "") . (!is_null($promo->code ?? null) ? " скидка за промокод '$promo->code' составляет $promo->discount руб. (уже учтена)" : "");

        $this->contactsPrepare($data);

        $userInfo = !$needPickup ?
            sprintf(($whenReady ? "🟢" : "🟡") . "Заказ №: %s\nИдентификатор клиента: %s\nДанные для доставки:\nФ.И.О.: %s\nНомер телефона: %s\nАдрес: %s\nЦена доставки(тест): %s \nДистанция(тест): %s \nНомер подъезда: %s\nНомер этажа: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nДоставить ко времени:%s\nЧисло персон: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id ?? '-',
                $data["name"] ?? 'Не указано',
                $data["phone"] ?? 'Не указано',
                $address . "," . ($data["flat_number"] ?? ""),
                $deliveryPrice ?? 0, //$distance
                $distance ?? 0, //$distance
                $data["entrance_number"] ?? 'Не указано',
                $data["floor_number"] ?? 'Не указано',
                $cash,
                $data["money"] ?? 'Не указано',
                $data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            ) : sprintf(($whenReady ? "🟢" : "🟡") . "Заказ №: %s\nИдентификатор: %s\nДанные для самовывоза:\nФ.И.О.: %s\nНомер телефона: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nЗаберу в:%s\nЧисло персон: %s\n",
                $order->id ?? '-',
                $this->botUser->telegram_chat_id,
                $data["name"] ?? 'Не указано',
                $data["phone"] ?? 'Не указано',
                $cash,
                $data["money"] ?? 'Не указано',
                $data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            );

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';

        $paymentInfo = sprintf((Collection::make($this->slug->config)
            ->where("key", "payment_info")
            ->first())["value"] ?? "Оплатите заказ по реквизитам:\nСбер XXXX-XXXX-XXXX-XXXX Иванов И.И. или переводом по номеру +7(000)000-00-00 - указав номер %s\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>",
            $userId);

        $botDomain = $this->bot->bot_domain;
        $link = "https://t.me/$botDomain?start=" . base64_encode("003" . $this->botUser->telegram_chat_id);

        $keyboard = [
            [
                ["text" => "✉Работа с заказом пользователя", "url" => $link]
            ]
        ];


        $thread = $this->bot->topics["delivery"] ?? null;

        $tmpUserLink = "\n<a href='tg://user?id=$userId'>Перейти к чату с пользователем</a>\n";

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->bot->order_channel ?? null,
                "$message\n\n$userInfo\n$tmpUserLink",
                $keyboard,
                $thread
            );

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->botUser->telegram_chat_id,
                ("Спасибо, ваш заказ появился в нашей системе:\n\n<em>$message</em>\n\n$paymentInfo" ?? "Данные не найдены") .
                "\nВы можете оставить отзыв с фото и получить от нас дополнительный КэшБэк!",
                [
                    [
                        ["text" => "📢Написать отзыв с фото", "web_app" => [
                            "url" => env("APP_URL") . "/bot-client/simple/" . $this->bot->bot_domain . "?slug=route&hide_menu#/s/feedback"
                        ]],
                    ],
                ]
            );


        $mpdf = new Mpdf();
        $current_date = Carbon::now("+3:00")->format("Y-m-d H:i:s");

        $number = Str::uuid();


        $mpdf->WriteHTML(view("pdf.order", [
            "title" => $this->bot->title ?? $this->bot->bot_domain ?? 'CashMan',
            "uniqNumber" => $number,
            "orderId" => $order->id,
            "name" => $order->receiver_name,
            "phone" => $order->receiver_phone,
            "address" => $address . "," . ($data["flat_number"] ?? ""),
            "message" => ($data["info"] ?? 'Не указано'),
            "entranceNumber" => ($data["entrance_number"] ?? 'Не указано'),
            "floorNumber" => ($data["floor_number"] ?? 'Не указано'),
            "cashType" => $cash,
            "money" => ($data["money"] ?? 'Не указано'),
            "disabilitiesText" => ($disabilitiesText ?? 'не указаны'),
            "totalPrice" => $summaryPrice,
            "discount" => $useCashback ? $discount : 0,
            "totalCount" => $summaryCount,
            "distance" => $distance ?? 0, //$distance
            "deliveryPrice" => $deliveryPrice ?? 0, //цена доставки
            "currentDate" => $current_date,
            "code" => "Без промокода",
            "promoCount" => "0",
            "paymentInfo" => $paymentInfo,
            "products" => $tmpOrderProductInfo,
            "info" => $data["info"] ?? 'Не указано',
        ]));

        $file = $mpdf->Output("order-$number.pdf", \Mpdf\Output\Destination::STRING_RETURN);

        if (!$needPaymentLink)
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendDocument(
                    $this->botUser->telegram_chat_id,
                    "Информация о заказе #" . ($order->id ?? 'не указан'),
                    InputFile::createFromContents($file, "invoice.pdf")
                );
        else {
            BusinessLogic::payment()
                ->setBot($this->bot)
                ->setBotUser($this->botUser)
                ->setSlug($this->slug)
                ->checkout([
                    "products" => $tmpProducts
                ]);
        }

        if (!is_null($this->bot->frontPad ?? null))
            BusinessLogic::frontPad()
                ->setBot($this->bot)
                ->setBotUser($this->botUser)
                ->newOrder([
                    "products" => $tmpOrderProductInfo,
                    "phone" => $order->receiver_phone,
                    "descr" => $data["info"] ?? 'Не указано',
                    "name" => $order->receiver_name,
                    "home" => ($data["building"] ?? ""),
                    "street" => ($data["street"] ?? ""),
                    'pod' => ($data["entrance_number"] ?? 'Не указано'),
                    'et' => ($data["floor_number"] ?? 'Не указано'),
                    'apart' => ($data["flat_number"] ?? ""),
                    'person' => $persons,
                    'datetime' => ($whenReady ? null
                        : Carbon::parse($time)->format('Y-m-d H:i:s')),
                    'cash' => $cash
                ]);


        if ($useCashback) {

            $adminBotUser = BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->orderBy("updated_at", "desc")
                ->first();

            if (!is_null($adminBotUser))
                BusinessLogic::administrative()
                    ->setBotUser($adminBotUser)
                    ->setBot($this->bot ?? null)
                    ->removeCashBack([
                        "user_telegram_chat_id" => $this->botUser->telegram_chat_id,
                        "amount" => $discount ?? 0,
                        "info" => "Автоматическое списание скидки на покупку товара",
                    ]);
        }

        if (!is_null($uploadedPhoto)) {
            $ext = $uploadedPhoto->getClientOriginalExtension();

            $imageName = Str::uuid() . "." . $ext;

            $uploadedPhoto->storeAs("$imageName");

            $thread = $bot->topics["orders"] ?? null;
            $botUserTelegramChatId = $this->botUser->telegram_chat_id;

            $historyLink = "https://t.me/" . ($this->bot->bot_domain) . "?start=" . (
                !is_null($order) ?
                    base64_encode("001" . ($botUserTelegramChatId) . "O" . $order->id) :
                    base64_encode("001" . ($botUserTelegramChatId))
                );

            $channel = $this->bot->order_channel ?? $this->bot->main_channel ?? null;

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendPhoto(
                    $channel,
                    "#оплатачеком\n" .
                    ($whenReady ? "🟢" : "🟡") . "Заказ №:" . ($order->id ?? '-') . "\n" .
                    "Идентификатор клиента: " . ($this->botUser->telegram_chat_id ?? '-') . "\n" .
                    "Пользователь: " . ($order->receiver_name ?? '-') . "\n" .
                    "Телефон: " . ($order->receiver_phone ?? '-') . "\n\n" .
                    "Пояснение к оплате: " . ($data["image_info"] ?? 'не указано') .
                    "\n<a href='tg://user?id=$botUserTelegramChatId'>Перейти к чату с пользователем</a>\n",
                    InputFile::create(storage_path() . "/app/$imageName"), [
                    [
                        ["text" => "📜Заказ пользователя", "url" => $historyLink]
                    ],

                ],
                    $thread
                );

        }

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function checkout(array $data): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Требования функции не выполнены!");

        $validator = Validator::make($data, [
            "ids" => "required|array"
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $taxSystemCode = $this->bot->company->vat_code ?? 1;

        $products = Product::query()
            ->whereIn("id", $data["ids"]);

        $prices = [];
        $currency = "RUB";
        $providerData = (object)[
            "receipt" => []
        ];

        $summaryDescription = "";
        $summaryPrice = 0;
        foreach ($products as $product) {
            if ($product->current_price < 10000) {
                continue;
            }

            $prices[] = [
                "label" => $product->title,
                "amount" => $product->current_price,
            ];

            $summaryDescription .= "$product->title\n";

            $providerData->receipt[] = (object)[
                "description" => "$product->title $product->description",
                "quantity" => "1.00",
                "amount" => (object)[
                    "value" => $product->current_price / 100,
                    "currency" => $currency
                ],
                "vat_code" => $taxSystemCode
            ];

            $summaryPrice += $product->current_price;

        }


        $payload = bin2hex(Str::uuid());

        $providerToken = $this->bot->payment_provider_token;


        Transaction::query()->create([
            'user_id' => $this->botUser->user_id,
            'bot_id' => $this->bot->id,
            'payload' => $payload,
            'currency' => $currency,
            'total_amount' => $summaryPrice,
            'status' => 0,
            'products_info' => (object)[
                "payload" => $payloadData ?? null,
                "prices" => $prices,
            ],
        ]);

        $needs = [
            "need_name" => true,
            "need_phone_number" => true,
            "need_email" => true,
            "need_shipping_address" => false,
            "send_phone_number_to_provider" => true,
            "send_email_to_provider" => true,
            "is_flexible" => false,
            "disable_notification" => false,
            "protect_content" => false,
        ];


        $keyboard = [
            [
                ["text" => "Оплатить товар (" . ($summaryPrice / 100) . "₽)", "pay" => true],
            ],

        ];


        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInvoice(
                $this->botUser->telegram_chat_id,
                "Оплата товара", $summaryDescription, $prices, $payload, $providerToken, $currency, $needs, $keyboard,
                $providerData
            );
    }

    /**
     * @throws HttpException
     */
    public function productsInCategory($categoryId, $search = null, $size = null): ProductCollection
    {

        if (is_null($this->bot))
            throw new HttpException(404, "Требования функции не выполнены!");

        $size = $size ?? config('app.results_per_page');

        $products = Product::query()
            ->with(["productCategories", "productOptions"])
            ->where("bot_id", $this->bot->id);

        if (!is_null($search))
            $products = $products
                ->where(function ($q) use ($search) {
                    $q->where("title", "like", "%$search%")
                        ->orWhere("description", "like", "%$search%");
                });

        $products = $products
            ->whereRelation('productCategories', 'id', $categoryId)
            /*->whereHas("productCategories", function ($query) use ($categoryId){
                $query->where("id", $categoryId);
            })*/
            ->orderBy("created_at", "DESC")
            ->paginate($size);

        return new ProductCollection($products);
    }

    /**
     * @throws HttpException
     */
    public function category($categoryId): ProductCategoryResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Требования функции не выполнены!");

        $category = ProductCategory::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $categoryId)
            ->first();

        return new ProductCategoryResource($category);
    }

    /**
     * @param array $data
     * @return void
     */
    private function contactsPrepare(array $data): void
    {
        $vowels = ["(", ")", "-"];
        $filteredPhone = !is_null($data["phone"] ?? $this->botUser->phone ?? null) ?
            str_replace($vowels, "", $data["phone"] ?? $this->botUser->phone) : null;

        $this->botUser->name = $data["name"] ?? $this->botUser->name ?? null;
        $this->botUser->phone = $filteredPhone;
        $this->botUser->save();
    }
}
