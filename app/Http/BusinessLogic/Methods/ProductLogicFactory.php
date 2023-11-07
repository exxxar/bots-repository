<?php

namespace App\Http\BusinessLogic\Methods;

use App\Facades\BotMethods;
use App\Http\BusinessLogic\Methods\Utilites\LogicUtilities;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\Transaction;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductLogicFactory
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

    /**
     * @throws HttpException
     */
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
    public function setSlug($slug): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
        return $this;
    }

    /**
     * @throws HttpException
     */
    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    public function byIds(array $ids = []): ProductCollection
    {
        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();

        return new ProductCollection($products);
    }

    /**
     * @throws HttpException
     */
    public function list($search = null, array $filters = null, $size = null): ProductCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $products = Product::query()
            ->with(["productCategories", "productOptions"])
            ->where("bot_id", $this->bot->id);

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
    public function categories($size = 100): ProductCategoryCollection
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $size = $size ?? config('app.results_per_page');

        $categories = ProductCategory::query()
            ->where("bot_id", $this->bot->id)
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
     * @throws ValidationException
     * @throws HttpException
     */
    public function createOrUpdate(array $data, array $uploadedPhotos): ProductResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "article" => "required",
            "title" => "required",
            "description" => "required",
            "type" => "required",
            "current_price" => "required",
            "in_stop_list_at" => "",
            "bot_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $slug = $this->bot->company->slug;

        $photos = $this->uploadPhotos("/public/companies/$slug", $uploadedPhotos);

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
            'article' => $data["article"],
            'vk_product_id' => $data["vk_product_id"] ?? null,
            'title' => $data["title"] ?? null,
            'description' => $data["description"] ?? null,
            'images' => $images,
            'type' => $data["type"] ?? 0,
            'old_price' => $data["old_price"] ?? 0,
            'current_price' => $data["current_price"] ?? 0,
            'variants' => $variants,
            'in_stop_list_at' => ($data["in_stop_list_at"] ?? false) == "true" ? Carbon::now() : null,
            'bot_id' => $data["bot_id"] ?? null,
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

        $category = ProductCategory::query()
            ->create([
                'title' => $data["category"],
                'bot_id' => $this->bot->id,
            ]);

        return new ProductCategoryResource($category);
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

        return new ProductResource($tmpProduct);
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
    public function checkoutInformation(array $data): void
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Требования функции не выполнены!");

        $validator = Validator::make($data, [
            "products" => "required",
            "name" => "required",
            "phone" => "required",
            "address" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tmpProducts = json_decode($data["products"]);
        $ids = Collection::make($tmpProducts)
            ->pluck("id");

        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();

        $message = "";
        $summaryPrice = 0;
        $summaryCount = 0;
        foreach ($products as $product) {

            $tmpCount = array_filter($tmpProducts, function ($item) use ($product) {
                return $item->id === $product->id;
            })[0]->count ?? 0;

            $tmpPrice = ($product->current_price ?? 0) * $tmpCount;
            $message .= sprintf("%s x%s=%s руб.\n",
                $product->title,
                $tmpCount,
                $tmpPrice
            );

            $summaryCount += $tmpCount;
            $summaryPrice += $tmpPrice;
        }

        $message .= "Итого: $summaryPrice руб. за $summaryCount ед.";

        $userInfo = sprintf("Данные для доставки:\nФ.И.О.:%s\nНомер телефона:%s\nАдрес:%s\nДоп.инфо:%s\n",
            $data["name"] ?? 'Не указано',
            $data["phone"] ?? 'Не указано',
            $data["address"] ?? 'Не указано',
            $data["info"] ?? 'Не указано',
        );

        $userId = $this->botUser->telegram_chat_id ?? 'Не указан';
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->bot->order_channel ?? $this->bot->main_channel ?? null,
                "#заказдоставка\n\n$message\n\n$userInfo"
            )
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Спасибо, ваш заказ появился в нашей системе:\n\n<em>$message</em>\n\nОплатите заказ по реквизитам:\nСбер 2222 2222 2222 2222 Егор Ш. или переводом по номеру +7(000)000-00-00 - указав номер  $userId\nИ отправьте нам скриншот оплаты со словом <strong>оплата</strong>" ?? "Данные не найдены"
            );
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
}
