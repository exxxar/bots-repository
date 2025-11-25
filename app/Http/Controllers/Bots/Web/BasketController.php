<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasketCollection;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BasketController extends Controller
{
    public function loadProductsInBasket(Request $request): BasketCollection
    {
        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInBasket($request->table_id ?? null);
    }

    public function commentProductInBasket(Request $request): BasketCollection
    {


        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->addProductComment($request->all());
    }
    /**
     * @throws ValidationException
     */
    public function incProductInBasket(Request $request): BasketCollection
    {


        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->addAndIncrementProduct($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function incCollectionInBasket(Request $request): BasketCollection
    {
        $variantId = $request->variant_id ?? null;
        return is_null($variantId) ?
            BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->addCollection($request->all()) :
            BusinessLogic::basket()
                ->setBot($request->bot ?? null)
                ->setBotUser($request->botUser ?? null)
                ->incrementCollection($request->all());
    }


    public function decProductInBasket(Request $request): BasketCollection
    {
        $request->validate([
            "product_id" => "required"
        ]);

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->decrementAndRemoveProduct($request->product_id ?? null);
    }

    public function decCollectionInBasket(Request $request): BasketCollection
    {
        $request->validate([
            "product_collection_id" => "required"
        ]);

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->decrementAndRemoveCollection($request->all());
    }


    public function clearBasket(Request $request): BasketCollection
    {
        BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->clearBasket();

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInBasket();
    }

    public function removeBasketItem(Request $request, $id): BasketCollection
    {
        BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->removeFromBasket($id);

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInBasket();

    }

    public function incrementItem(Request $request, $id): BasketCollection
    {
        BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->increment($id);

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInBasket();

    }

    public function decrementItem(Request $request, $id): BasketCollection
    {
        BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->decrement($id);

        return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->productsInBasket();

    }

    /**
     * @throws ValidationException
     */
    public function checkout(Request $request)
    {
       return BusinessLogic::basket()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkout($request->all(),
                $request->hasFile('photo') ? $request->file('photo') : null
            );


    }


}
