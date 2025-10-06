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
    public function checkout(array $data, $uploadedPhoto = null): object | null
    {

        if (is_null($this->bot) || is_null($this->botUser) )
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
    public function productsInBasket(): BasketCollection
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $allProductsInBasket = Basket::query()
            ->with(["collection"])
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("table_approved_at")
            ->whereNull("ordered_at")
            ->get();

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addCollection(array $data): BasketCollection
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

        $allProductsInBasket = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->get();

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function incrementCollection(array $data): BasketCollection
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

        $allProductsInBasket = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws HttpException
     */
    public function decrement($itemId): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $productInBasket = Basket::query()
            ->where(function ($q) use ($itemId) {
                return $q->where("product_id", $itemId)
                    ->orWhere("product_collection_id", $itemId);
            })
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (is_null($productInBasket))
            throw new HttpException(404, "Товар в корзине не найден!");


        if ($productInBasket->count - 1 > 0) {
            $productInBasket->count--;
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
            ->where(function ($q) use ($itemId) {
                return $q->where("product_id", $itemId)
                    ->orWhere("product_collection_id", $itemId);
            })
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        if (is_null($productInBasket))
            throw new HttpException(404, "Товар в корзине не найден!");

        $productInBasket->count++;
        $productInBasket->save();

    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addProductComment(array $data): BasketCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $validator = Validator::make($data, [
            "product_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $productId = $data["product_id"] ?? null;

        $product = Product::query()
            ->where("bot_id", $this->bot->id)
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

        if (!is_null($productInBasket))
        {
            $productInBasket->comment = $data["comment"] ?? null;
            $productInBasket->save();

        }

        $allProductsInBasket = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws ValidationException
     * @throws HttpException
     */
    public function addAndIncrementProduct(array $data): BasketCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $validator = Validator::make($data, [
            "product_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $productId = $data["product_id"] ?? null;
        $productCount = $data["count"] ?? 1;

        $product = Product::query()
            ->where("bot_id", $this->bot->id)
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


        $tableWithClient = Table::query()
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->whereHas('clients', function ($query) {
                $query->where('id', $this->botUser->id);
            })->first();


        if (is_null($productInBasket)) {
            $productInBasket = Basket::query()->create([
                'product_id' => $product->id,
                'count' => $productCount,
                'bot_user_id' => $this->botUser->id,
                'table_id' => $tableWithClient->id ?? null,
                'bot_id' => $this->bot->id,
                'ordered_at' => null,
                'table_approved_at' => null,
            ]);

        } else {
            $productInBasket->count += $productCount;
            $productInBasket->save();

        }

        $allProductsInBasket = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        return new BasketCollection($allProductsInBasket);
    }

    /**
     * @throws HttpException
     */
    public function clearBasket(): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");


        $baskets = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        foreach ($baskets as $basket) {
            $basket->ordered_at = Carbon::now();
            $basket->save();
        }

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
    public function decrementAndRemoveCollection(array $data): BasketCollection
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

        $allProductsInBasket = Basket::query()
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->get();

        return new BasketCollection($allProductsInBasket);

    }

    /**
     * @throws HttpException
     */
    public function decrementAndRemoveProduct($productId): BasketCollection
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

        $allProductsInBasket = Basket::query()
            ->with(["product"])
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNull("table_approved_at")
            ->first();

        return new BasketCollection($allProductsInBasket);

    }


}
