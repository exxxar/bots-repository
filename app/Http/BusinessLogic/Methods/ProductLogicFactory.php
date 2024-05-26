<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
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
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

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
    public function categories($size = null): ProductCategoryCollection
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
    public function createOrUpdate(array $data, array $uploadedPhotos = null): ProductResource
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "article" => "",
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


        $photos = !is_null($uploadedPhotos) ?
            $this->uploadPhotos("/public/companies/$slug", $uploadedPhotos) : [];

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

        $usePaymentSystem = (Collection::make($this->slug->config)
            ->where("key", "use_payment_system")
            ->first())["value"] ?? false;

        $products = Product::query()
            ->whereIn("id", $ids)
            ->get();

        $needPickup = ($data["need_pickup"] ?? "false") == "true";
        $hasDisability = ($data["has_disability"] ?? "false") == "true";
        $useCashback = ($data["use_cashback"] ?? "false") == "true";
        $cash = ($data["cash"] ?? "false") == "true";
        $message = (!$needPickup ? "#заказдоставка\n\n" : "#заказсамовывоз\n\n");

        $persons = $data["persons"] ?? 1;
        $time = $data["time"] ?? null;
        $whenReady = ($data["when_ready"] ?? "false") == "true";

        $summaryPrice = 0;
        $summaryCount = 0;

        $disabilities = json_decode($data["disabilities"] ?? '[]');

        if ($hasDisability) {

            $disabilitiesText = "<b>Внимание!</b> у клиента присутствуют ограничения по здоровью!\n";

            foreach ($disabilities as $disability)
                $disabilitiesText .= "-<em>$disability</em>\n";

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

        $maxUserCashback = $this->botUser->cashback->amount ?? 0;
        $botCashbackPercent = $this->bot->max_cashback_use_percent ?? 0;
        $cashBackAmount = ($summaryPrice * ($botCashbackPercent / 100));
        $discount = min($cashBackAmount, $maxUserCashback);

        $deliveryNote = ($data["info"] ?? 'Не указано') . "\n"
            . "Номер подъезда: " . ($data["entrance_number"] ?? 'Не указан') . "\n"
            . "Номер этажа: " . ($data["floor_number"] ?? 'Не указан') . "\n"
            . "Тип оплаты: " . ($cash ? "Наличкой" : "Картой") . "\n"
            . "Сдача с:" . ($data["money"] ?? 'Не указано') . "\n"
            . "Время доставки:" . ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')) . "\n"
            . "Число персон:" . $persons . "\n"
            . "Ограничения пользователя:\n" . ($disabilitiesText ?? 'не указаны');

        $address = (($data["city"] ?? "") . "," . ($data["street"] ?? "") . "," . ($data["building"] ?? ""));


        $geo = BusinessLogic::geo()
            ->setBot($this->bot ?? null)
            ->getCoords([
                "address" => $address
            ]);

        $shopCoords = (Collection::make($this->slug->config)
            ->where("key", "shop_coords")
            ->first())["value"] ?? null;


        if (!is_null($shopCoords) && !$needPickup) {
            $coords = explode(',', $shopCoords);

            $coordsData = [
                "coords" => [
                    (object)[
                        "lat" => $geo->latitude ?? 0,
                        "lon" => $geo->longitude ?? 0,
                    ],
                    (object)[
                        "lat" => $coords[0] ?? 0,
                        "lon" => $coords[1] ?? 0,
                    ],
                ]
            ];

            $distanceObject = BusinessLogic::geo()
                ->setBot($this->bot ?? null)
                ->getDistance($coordsData);

            $distance = $distanceObject->distance ?? 0;

        }

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
            'delivery_price' => 0,
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

        $message .= "Итого: $summaryPrice руб. за $summaryCount ед. Скидка: $discount руб.";

        $userInfo = !$needPickup ?
            sprintf("Идентификатор: %s\nДанные для доставки:\nФ.И.О.: %s\nНомер телефона: %s\nАдрес: %s\nДистанция(тест): %s м\nНомер подъезда: %s\nНомер этажа: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nДоставить ко времени:%s\nЧисло персон: %s\n",
                $this->botUser->telegram_chat_id,
                $data["name"] ?? 'Не указано',
                $data["phone"] ?? 'Не указано',
                $address . "," . ($data["flat_number"] ?? ""),
                $distance ?? 0, //$distance
                $data["entrance_number"] ?? 'Не указано',
                $data["floor_number"] ?? 'Не указано',
                ($cash ? "Наличкой" : "Картой"),
                $data["money"] ?? 'Не указано',
                $data["info"] ?? 'Не указано',
                $useCashback ? $discount : "нет",
                ($whenReady ? "По готовности" : Carbon::parse($time)->format('Y-m-d H:i')),
                $persons
            ) : sprintf("Идентификатор: %s\nДанные для самовывоза:\nФ.И.О.: %s\nНомер телефона: %s\nТип оплаты: %s\nСдача с: %s руб.\nДоп.инфо: %s\nИспользован кэшбэк: %s\nЗаберу в:%s\nЧисло персон: %s\n",
                $this->botUser->telegram_chat_id,
                $data["name"] ?? 'Не указано',
                $data["phone"] ?? 'Не указано',
                ($cash ? "Наличкой" : "Картой"),
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
                ["text" => "✉Написать пользователю ответ", "url" => $link]
            ]
        ];

        $thread = $this->bot->topics["delivery"] ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->bot->order_channel ?? null,
                "$message\n\n$userInfo",
                $keyboard,
                $thread
            );

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Спасибо, ваш заказ появился в нашей системе:\n\n<em>$message</em>\n\n$paymentInfo" ?? "Данные не найдены"
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
            "cashType" => ($cash ? "Наличкой" : "Картой"),
            "money" => ($data["money"] ?? 'Не указано'),
            "disabilitiesText" => ($disabilitiesText ?? 'не указаны'),
            "totalPrice" => $summaryPrice,
            "discount" => $useCashback ? $discount : 0,
            "totalCount" => $summaryCount,
            "distance" => $distance ?? 0, //$distance
            "currentDate" => $current_date,
            "code" => "Без промокода",
            "promoCount" => "0",
            "paymentInfo" => $paymentInfo,
            "products" => $tmpOrderProductInfo,
            "info" => $data["info"] ?? 'Не указано',
        ]));

        $file = $mpdf->Output("order-$number.pdf", \Mpdf\Output\Destination::STRING_RETURN);

        if (!$usePaymentSystem)
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendDocument(
                    $this->botUser->telegram_chat_id,
                    "Счет на оплату заказа #" . ($order->id ?? 'не указан'),
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
