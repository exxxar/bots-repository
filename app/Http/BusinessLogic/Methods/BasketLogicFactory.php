<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BasketCollection;
use App\Http\Resources\BasketResource;
use App\Models\ActionStatus;
use App\Models\AmoCrm;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\Table;
use Carbon\Carbon;
use CdekSDK2\Exceptions\RequestException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class BasketLogicFactory extends BaseLogicFactory
{


    /**
     * @throws ValidationException
     * @throws HttpException
     * @throws RequestException
     */
    public function checkout(array $data, $uploadedPhoto = null): object|null
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Требования функции не выполнены!");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
        ]);


        if ($validator->fails())
            throw new ValidationException($validator);

        $basket = new \App\Http\BusinessLogic\Methods\Classes\Basket(
            $data,
            $this->bot,
            $this->botUser,
            //   $this->slug,
            $uploadedPhoto
        );

        try {
            return $basket->handler();
        } catch (RequestException|ValidationException $e) {
            return null;
        }

    }


    /**
     * @throws HttpException
     */
    public function productsInBasket($tableId = null): BasketCollection
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $allProductsInBasket = is_null($tableId) ? Basket::query()
            ->with(['product' => fn($q) => $q->withTrashed()])
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("table_approved_at")
            ->whereNull("ordered_at")
            ->get() :
            Basket::query()
                ->with(['product' => fn($q) => $q->withTrashed()])
                ->where("bot_user_id", $this->botUser->id)
                ->where("table_id", $tableId)
                ->where("bot_id", $this->bot->id)
                ->whereNull("table_approved_at")
                ->whereNull("ordered_at")
                ->get();

        foreach ($allProductsInBasket as $item) {
            if (!is_null($item->product->deleted_at)) {
                $item->delete();
            }
        }

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addCollection(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");


        $validator = Validator::make($data, [
            "product_collection" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $productCollection = (object)$data["product_collection"];

        $variantId = $data["variant_id"] ?? null;

        $productCollectionId = $productCollection->id ?? null;

        $collection = ProductCollection::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $productCollectionId)
            ->first();

        if (is_null($collection))
            throw new HttpException(404, "Коллекция не найдена в системе!");


        $productsInBasket = Basket::query()
            ->where("product_collection_id", $collection->id)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        $ids = Collection::make($productCollection->products)
            ->where("is_checked", true)
            ->pluck("id");

        $tableWithClient = Table::query()
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->whereHas('clients', function ($query) {
                $query->where('id', $this->botUser->id);
            })->first();


        $tmp = [
            'product_collection_id' => $collection->id,
            'count' => 1,
            'bot_user_id' => $this->botUser->id,
            'bot_id' => $this->bot->id,
            'ordered_at' => null,
            'table_id' => $tableWithClient->id ?? null,
            'params' => (object)[
                "variant_id" => Str::uuid(),
                "ids" => $ids->toArray()
            ],
        ];

        if (count($productsInBasket) == 0) {
            Basket::query()->create($tmp);
        } else {

            $findVariant = false;
            foreach ($productsInBasket as $pib) {
                $params = (object)($pib->params ?? null);

                if (($params->variant_id ?? null) == $variantId && !is_null($variantId)) {
                    $findVariant = true;
                    $pib->count++;
                    $pib->save();
                }

            }

            if (!$findVariant)
                Basket::query()->create($tmp);


        }

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function incrementCollection(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $validator = Validator::make($data, [
            "product_collection_id" => "required",
            "variant_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $variantId = $data["variant_id"] ?? null;

        $productCollectionId = $data["product_collection_id"] ?? null;

        $collection = ProductCollection::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $productCollectionId)
            ->first();

        if (is_null($collection))
            throw new HttpException(404, "Коллекция не найдена в системе!");


        $productsInBasket = Basket::query()
            ->where("product_collection_id", $collection->id)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();


        if (count($productsInBasket) != 0) {
            foreach ($productsInBasket as $pib) {
                $params = (object)($pib->params ?? null);

                if (($params->variant_id ?? null) == $variantId) {
                    $pib->count++;
                    $pib->save();


                }
            }


        }


    }

    /**
     * @throws HttpException
     */
    public function decrement($itemId): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $productInBasket = Basket::query()
            ->with(['product' => fn($q) => $q->withTrashed()])
            ->where(function ($q) use ($itemId) {
                return $q->where("product_id", $itemId)
                    ->orWhere("product_collection_id", $itemId);
            })
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (!is_null($productInBasket->product->deleted_at ?? null)) {
            $productInBasket->delete();
            throw new HttpException(403, "Товар не найден!");
        }

        if (is_null($productInBasket))
            throw new HttpException(404, "Товар в корзине не найден!");


        $productCount = 1;

        $product = $productInBasket->product;

        if ($product->is_weight_product ?? false) {

            $weightConfig = (object)$product->weight_config ?? null;
            $min = $weightConfig->min ?? 0;
            $max = $weightConfig->max ?? 0;
            $step = $weightConfig->step ?? 0;

            $productCount = is_null($productInBasket) ? $min : $step;


            if (($productInBasket->count ?? 0) <= $min)
                $productCount = $min;

        }

        if ($productInBasket->count - $productCount > 0) {
            $productInBasket->count -= $productCount;
            $productInBasket->save();
        } else
            $productInBasket->delete();
    }

    /**
     * @throws HttpException
     */
    public function increment($itemId): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $productInBasket = Basket::query()
            ->with(['product' => fn($q) => $q->withTrashed()])
            ->where(function ($q) use ($itemId) {
                return $q->where("product_id", $itemId)
                    ->orWhere("product_collection_id", $itemId);
            })
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (is_null($productInBasket)) {
            throw new HttpException(404, "Товар в корзине не найден!");
        }

        if (!is_null($productInBasket->product->deleted_at ?? null)) {
            $productInBasket->delete();
            throw new HttpException(403, "Товар не найден!");
        }


        $productCount = 1;
        $product = $productInBasket->product;

        if ($product && $product->is_weight_product ?? false) {

            $weightConfig = is_array($product->weight_config)
                ? (object)$product->weight_config
                : json_decode($product->weight_config ?? '{}');

            $min = $weightConfig->min ?? 0;
            $max = $weightConfig->max ?? 0;
            $step = $weightConfig->step ?? 0;

            $productCount = $productInBasket->count == 0 ? $min : $step;

            if (($productInBasket->count ?? 0) >= $max && $max > 0)
                $productCount = 0;
        }

        $productInBasket->count += $productCount;
        $productInBasket->save();

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addProductComment(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $validator = Validator::make($data, [
            "product_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $botIds = [$this->bot->id, ...$this->bot->partners()->get()->pluck("bot_partner_id")];

        $productId = $data["product_id"] ?? null;

        $product = Product::query()
            ->whereIn("bot_id", $botIds)
            ->where("id", $productId)
            ->first();

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден в системе!");

        $productInBasket = Basket::query()
            ->where("product_id", $product->id)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (!is_null($productInBasket)) {
            $productInBasket->comment = $data["comment"] ?? null;
            $productInBasket->save();

        }


    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addAndIncrementProduct(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $validator = Validator::make($data, [
            "product_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $config = $this->bot->config ?? [];
        $hasPartners = $config["partners"]["is_active"] ?? false;

        $botIds = $hasPartners ?
            [$this->bot->id, ...$this->bot->partners()->get()->pluck("bot_partner_id")] :
            [$this->bot->id];

        $productId = $data["product_id"] ?? null;
        $productCount = $data["count"] ?? 1;

        $tableId = $data["table_id"] ?? null;

        $product = Product::query()
            ->withTrashed()
            ->whereIn("bot_id", $botIds)
            ->where("id", $productId)
            ->first();

        if (is_null($product))
            throw new HttpException(404, "Продукт не найден в системе!");

        if (!is_null($product->deleted_at)) {
            $product->delete();
            throw new HttpException(403, "Продукт недоступен!");
        }


        $productInBasket = Basket::query()
            ->where("product_id", $product->id)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();


        $tableWithClient = is_null($tableId) ? Table::query()
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->whereHas('clients', function ($query) {
                $query->where('id', $this->botUser->id);
            })->first() :
            Table::query()
                ->where("bot_id", $this->bot->id)
                ->where("id", $tableId)
                ->whereNull("closed_at")
                ->first();

        $isWeightProduct = $product->is_weight_product ?? false;

        if ($isWeightProduct) {

            $weightConfig = (object)$product->weight_config ?? null;
            $min = $weightConfig->min ?? 0;
            $max = $weightConfig->max ?? 0;
            $step = $weightConfig->step ?? 0;


            $productCount = is_null($productInBasket) ? $min : $step;

            if (($productInBasket->count ?? 0) >= $max && $max > 0)
                $productCount = 0;
        }

        if (is_null($productInBasket)) {
            $extraCharge = 0;
            if ($product->bot_id != $this->bot->id) {
                $partner = Partner::query()
                    ->where("bot_id", $this->bot->id)
                    ->where("bot_partner_id", $product->bot_id)
                    ->first();

                $extraCharge = is_null($partner) ? 0 : $partner->extra_charge ?? 0;
            }

            $productInBasket = Basket::query()->create([
                'product_id' => $product->id,
                'count' => $productCount,
                'bot_user_id' => $this->botUser->id,
                'table_id' => $tableWithClient->id ?? null,
                'bot_id' => $this->bot->id,
                'bot_partner_id' => $product->bot_id == $this->bot->id ? null : $product->bot_id,
                'params' => [
                    "extra_charge" => $extraCharge
                ],
                'ordered_at' => null,
                'table_approved_at' => null,
            ]);


        } else {

            if (!is_null($tableId))
                $productInBasket->table_id = $tableId;
            $productInBasket->count += $productCount;
            $productInBasket->save();

        }


    }

    /**
     * @throws HttpException
     */
    public function clearBasket(): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->delete();

    }

    /**
     * @throws HttpException
     */
    public function removeFromBasket($itemId): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");


        $basket = Basket::query()
            ->where(function ($q) use ($itemId) {
                return $q->where("product_id", $itemId)
                    ->orWhere("product_collection_id", $itemId);
            })
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (is_null($basket))
            throw new HttpException(404, "Элемент не найден!");

        $basket->delete();

    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function decrementAndRemoveCollection(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");


        $validator = Validator::make($data, [
            "product_collection_id" => "required",
            "variant_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $variantId = $data["variant_id"] ?? null;

        $productCollectionId = $data["product_collection_id"] ?? null;

        $collection = ProductCollection::query()
            ->where("bot_id", $this->bot->id)
            ->where("id", $productCollectionId)
            ->first();

        if (is_null($collection))
            throw new HttpException(404, "Коллекция не найдена в системе!");

        $productsInBasket = Basket::query()
            ->where("product_collection_id", $collection->id)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();


        if (count($productsInBasket) != 0) {
            foreach ($productsInBasket as $pib) {
                $params = (object)($pib->params ?? null);

                if (($params->variant_id ?? null) == $variantId) {
                    if ($pib->count - 1 > 0) {
                        $pib->count--;
                        $pib->save();
                    } else
                        $pib->delete();

                }
            }


        }



    }

    /**
     * @throws HttpException
     */
    public function decrementAndRemoveProduct($productId)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $productInBasket = Basket::query()
            ->where("product_id", $productId)
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (is_null($productInBasket))
            throw new HttpException(404, "Товар не найден в корзине!");

        if ($productInBasket->count - 1 > 0) {
            $productInBasket->count--;
            $productInBasket->save();
        } else
            $productInBasket->delete();


    }


}
