<?php

namespace App\Http\Controllers\Bots\Web;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Controllers\Controller;
use App\Http\Resources\BasketCollection;

use App\Models\ActionStatus;
use App\Models\Basket;
use App\Models\BotUser;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class BasketController extends Controller
{

    public function useWheelOfFortunePrize(Request $request)
    {
        $request->validate([
            "action_prize" => "required"
        ]);

        $bot = $request->bot ?? null;
        $botUser = $request->botUser ?? null;

        $actionId = $request->action_id;

        $actionPrize = (object)$request->action_prize;

        $selectedPrizeDescription = $actionPrize->description ?? 'Без описания приза';
        $selectedPrizeWinId = (!is_null($actionPrize->win ?? null) ? json_decode($actionPrize->win) : null)->id ?? null;
        $playedAt = $actionPrize->played_at ?? null;

        $action = ActionStatus::query()
            ->find($actionId ?? null);

        if (!is_null($action)) {
            $tmpData = $action->data ?? [];
            $processedPrizes = [];

            foreach ($tmpData as $index => $item) {
                $item = (object)$item;
                $itemPrizeWinId = (!is_null($item->win ?? null) ? json_decode($item->win) : null)->id ?? null;

                if ($item->description == $selectedPrizeDescription &&
                    $itemPrizeWinId == $selectedPrizeWinId &&
                    !is_null($selectedPrizeWinId)) {
                    // Проверяем, не был ли этот приз уже обработан
                    $prizeKey = $selectedPrizeDescription . '_' . $selectedPrizeWinId;
                    if (in_array($prizeKey, $processedPrizes)) {
                        continue; // Если приз уже есть, пропускаем
                    }
                    $processedPrizes[] = $prizeKey; // Добавляем в обработанные

                    $tmpData[$index]["taked_at"] = Carbon::now();
                    $itemPrizeType = $tmpData[$index]["type"] ?? "text";
                    $itemPrizeEffectedValue = $tmpData[$index]["effect_value"] ?? 0;
                    $itemPrizeEffectedProduct = $tmpData[$index]["effect_product"] ?? null;

                    switch ($itemPrizeType) {
                        default:
                        case "text":
                            $tmpUserLink = "\n<a href='tg://user?id=$botUser->telegram_chat_id'>Перейти к чату с пользователем</a>\n";
                            $thread = $bot->topics["actions"] ?? null;
                            $prizeText = "<em><b>" . ($item->description ?? '-') . "</b></em> - ручной режим выдачи\n";
                            BotMethods::bot()
                                ->whereBot($bot)
                                ->sendMessage($bot->order_channel,
                                    "Пользователь хочет получить свой приз из колеса фортуны: $prizeText $tmpUserLink",
                                    $thread);
                            sleep(1);
                            BotMethods::bot()
                                ->whereBot($bot)
                                ->sendMessage($botUser->telegram_chat_id,
                                    "Вы запросили получение приза <em><b>" . ($item->description ?? '-') . "</b></em>");
                            break;
                        case "effect_product":
                            $basket = \App\Models\Basket::query()
                                ->with('product')
                                ->where('bot_id', $bot->id)
                                ->where('bot_user_id', $botUser->id)
                                ->where('product_id', $itemPrizeEffectedProduct->id ?? null)
                                ->whereNull('ordered_at')
                                ->first();

                            if (is_null($basket) && !is_null($itemPrizeEffectedProduct)) {

                                $product = Product::query()
                                    ->find($itemPrizeEffectedProduct->id);

                                if (is_null($product))
                                    break;

                                $params = [];
                                $params["discount_price"] = $product->current_price - ($product->current_price * ($itemPrizeEffectedValue / 100));
                                $params["discount_amount"] = $product->current_price * ($itemPrizeEffectedValue / 100);

                                Basket::query()->create([
                                    "bot_id" => $bot->id,
                                    'bot_user_id' => $botUser->id,
                                    'product_id' => $product->id,
                                    'count' => 1,
                                    'params' => $params
                                ]);
                            }
                            break;
                        case "delivery_discount":
                            break;
                        case "product_discount":

                            $basket = \App\Models\Basket::query()
                                ->with('product')
                                ->where('bot_id', $bot->id)
                                ->where('bot_user_id', $botUser->id)
                                ->whereNull('ordered_at')
                                ->get();

                            foreach ($basket as $b) {
                                $params = $b->params ?? [];
                                $params["discount_price"] = $b->product->current_price - ($b->product->current_price * ($itemPrizeEffectedValue / 100));
                                $params["discount_amount"] = $b->product->current_price * ($itemPrizeEffectedValue / 100);

                                $b->params = $params;
                                $b->save();
                            }

                            break;
                        case "cashback":
                            $adminBotUser = BotUser::query()
                                ->where("bot_id", $this->bot->id)
                                ->where("is_admin", true)
                                ->first();

                            $userId = $this->botUser->user_id;

                            if (!is_null($adminBotUser))
                                event(new CashBackEvent(
                                    (int)$this->bot->id,
                                    (int)$userId,
                                    (int)$adminBotUser->user_id,
                                    ((float)$itemPrizeEffectedValue ?? 0),
                                    "Начисление баллов за колесо фортуны",
                                    CashBackDirectionEnum::Crediting
                                ));
                            break;
                    }
                }
            }

            $action->data = $tmpData;
            $action->save();
        }

        return response()->noContent();
    }

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
