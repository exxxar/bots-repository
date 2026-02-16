<?php

namespace App\Http\Controllers\API;

use App\Enums\CashBackDirectionEnum;
use App\Events\CashBackEvent;
use App\Facades\BotManager;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Classes\Banking\TinkoffBankService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\ActionStatus;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{

    public function sendSBPInvoice(Request $request)
    {
        $request->validate([
            "amount" => "required",
            "description" => "required"
        ]);

        BusinessLogic::payment()
            ->setSlug($request->slug ?? null)
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->invoiceLink($request->all());
    }

    private function ensureCityPrefix(string $address): string
    {
        // Список признаков города / населённого пункта
        $patterns = [
            '/\bг\.\b/ui',        // г.
            '/\bгород\b/ui',      // город
            '/\bс\.\b/ui',        // с.
            '/\bсело\b/ui',       // село
            '/\bпос\.\b/ui',      // пос.
            '/\bпос[её]лок\b/ui', // поселок / посёлок
            '/\bпгт\b/ui',        // пгт
        ];

        // Проверка наличия признака
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $address)) {
                return trim($address);
            }
        }

        // Если признака нет — добавляем "г."
        return 'город ' . trim($address);
    }

    private function ensureStreetPrefix(string $street): string
    {
        // Список признаков улицы
        $patterns = [
            '/\bул\.\b/ui',          // ул.
            '/\bулица\b/ui',         // улица
            '/\bпр-т\b/ui',          // пр-т
            '/\bпросп\.\b/ui',       // просп.
            '/\bпроспект\b/ui',      // проспект
            '/\bпер\.\b/ui',         // пер.
            '/\bпереулок\b/ui',      // переулок
            '/\bбул\.\b/ui',         // бул.
            '/\bбульвар\b/ui',       // бульвар
            '/\bпроезд\b/ui',        // проезд
            '/\bш\.\b/ui',           // ш.
            '/\bшоссе\b/ui',         // шоссе
            '/\bнаб\.\b/ui',         // наб.
            '/\bнабережная\b/ui',    // набережная
            '/\bпл\.\b/ui',          // пл.
            '/\bплощадь\b/ui',       // площадь
            '/\bтракт\b/ui',         // тракт
            '/\bтуп\.\b/ui',         // туп.
            '/\bтупик\b/ui',         // тупик
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $street)) {
                return trim($street);
            }
        }

        return 'улица ' . trim($street);
    }

    /**
     * @throws ValidationException
     */
    public function getDeliveryPrice(Request $request): \Illuminate\Http\JsonResponse
    {

        if (is_null($request->bot ?? null))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $request->validate([
            "city" => "required",
            "street" => "required",
            "building" => "required",
        ]);

        $bot = $request->bot ?? null;

        if (is_null($bot))
            return response()->json([
                "distance" => 0,
                "price" => 0,
                "config" => []
            ]);

        $config = $request->bot->config ?? null;

        $partners = \App\Models\Bot::query()
            ->whereIn('id', function ($q) use ($bot) {
                $q->select('bot_partner_id')
                    ->from('baskets')
                    ->where('bot_id', $bot->id)
                    ->whereNull('ordered_at');
            })
            ->distinct('bot_partner_id')
            ->get();

        $partners = [...$partners, $bot];

        if (is_null($config))
            return response()->json([
                "distance" => 0,
                "price" => 0,
                "address" => null,
                "config" => []
            ], 404);


        $sumDistance = 0;
        $sumPrice = 0;

        $partnerBoxConfig = [];

        $city = $request->city ?? "";
        $street = $this->ensureStreetPrefix($request->street ?? "");
        $address = "$city, $street, " . ($request->building ?? "");
        $geo = BusinessLogic::geo()
            ->getCoords([
                "address" => $address
            ]);

        foreach ($partners as $bot) {
            $config = $bot->config ?? [];
            $price_per_km = $config["price_per_km"] ?? 100;
            $min_base_delivery_price = $config["min_base_delivery_price"] ?? 100;

            $partnerBoxConfig[$bot->bot_domain] = (object)[
                "id" => $bot->id,
                "price" => 0,
                "title" => $bot->title ?? $bot->bot_domain ?? '-',
                "distance" => 0,
                "address" => $address,
                "shop_coords" => $bot->config["shop_coords"] ?? null,
                "client_coords" => $geo->lat . ", " . $geo->lon,
            ];


            if (($geo->lat ?? 0) > 0 && ($geo->lon ?? 0) > 0) {
                $tmpDistance = BusinessLogic::geo()
                    ->setBot($bot)
                    ->getDistance($geo->lat ?? 0, $geo->lon ?? 0);

                $distance = floatval($tmpDistance > 0 ? round($tmpDistance / 1000 ?? 0, 2) : 0);

                if ($distance < 100) {

                    $partnerBoxConfig[$bot->bot_domain]->distance = $distance;
                    $partnerBoxConfig[$bot->bot_domain]->price = round($min_base_delivery_price + $distance * $price_per_km, 2);

                    $sumDistance += $partnerBoxConfig[$bot->bot_domain]->distance;
                    $sumPrice += $partnerBoxConfig[$bot->bot_domain]->price;
                }
            }


        }

        return response()->json([
            "distance" => $sumDistance,
            "price" => $sumPrice,
            "address" => $address,
            "config" => $partnerBoxConfig,
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function loadRecommendedProducts(Request $request): ProductCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->loadRecommendedProducts();
    }


    /**
     * @throws ValidationException
     */
    public function getOrders(Request $request): \App\Http\Resources\OrderCollection
    {
        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->orderList($request->all(), $request->get("size") ?? config('app.results_per_page'));

    }

    public function getReviews(Request $request): \App\Http\Resources\ReviewCollection
    {


        return BusinessLogic::review()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->reviews($request->all(), $request->get("size") ?? config('app.results_per_page'));
    }

    public function getReviewsByProductId(Request $request): \App\Http\Resources\ReviewCollection
    {

        $request->validate([
            "product_id" => "required"
        ]);

        return BusinessLogic::review()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->reviewsByProductId($request->product_id, $request->get("size") ?? config('app.results_per_page'));
    }


    /**
     * @throws ValidationException
     */
    public function storeReview(Request $request): \App\Http\Resources\ReviewResource
    {
        $request->validate([
            'id' => "required",

        ]);

        return BusinessLogic::review()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->store($request->all(),
                $request->hasFile('photo') ?
                    $request->file('photo') : null);
    }


    public function loadOrderById(Request $request): \App\Http\Resources\OrderResource
    {
        $request->validate([
            "order_id" => "required"
        ]);

        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->getOrder($request->order_id ?? null);
    }

    public function declineOrder(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "order_id" => "required"
        ]);

        BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->declineOrder($request->order_id ?? null);

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function repeatOrder(Request $request): ProductCollection
    {
        $request->validate([
            "products" => "required"
        ]);

        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->repeatOrder($request->all());
    }


    public function loadMoreProductsByCategories(Request $request)
    {
        $request->validate([
            "category_id" => "required",
            "offset" => "required"
        ]);

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->loadMoreProductsByCategories(
                $request->category_id,
                $request->offset,
                $request->partner_id ?? null);
    }

    public function listByCategories(Request $request)
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->listByCategories($request->all());
    }

    public function index(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->list(
                search: $request->search ?? null,
                filters: [
                    "categories" => $request->categories ?? null,
                    "min_price" => $request->min_price ?? null,
                    "max_price" => $request->max_price ?? null
                ],

                size: $request->get("size") ?? config('app.results_per_page'),
                needRemoved: $request->get("need_removed") ?? false,
                needAll: $request->get("need_all") ?? false
            );
    }

    public function getFavList(Request $request): ProductCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->favList();
    }

    public function toggleProductInFavorites(Request $request)
    {
        $request->validate([
            "id" => "required"
        ]);

        return response()
            ->json([
                "favorites" => BusinessLogic::botUsers()
                    ->setBot($request->bot ?? null)
                    ->setBotUser($request->botUser ?? null)
                    ->toggleProductInFavorites($request->id)
            ]);
    }


    public function randomProducts(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->randomList();
    }


    /**
     * @throws ValidationException
     */
    public function createCheckoutLink(Request $request)
    {
        $request->validate([
            "products" => "required",
            "name" => "required",
            "phone" => "required",
        ]);

        return BusinessLogic::products()
            ->setSlug($request->slug ?? null)
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->createCheckoutLink($request->all());

    }

    /**
     * @throws ValidationException
     */
    public function checkoutInstruction(Request $request)
    {
        $request->validate([
            "products" => "required",
            "name" => "required",
            "phone" => "required",
        ]);


        BusinessLogic::products()
            ->setSlug($request->slug ?? null)
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkoutInformation($request->all(),
                $request->hasFile('photo') ? $request->file('photo') : null
            );

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function checkout(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "ids" => "required|array"
        ]);
        BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->checkout($request->all());

        return response()->noContent();
    }


}
