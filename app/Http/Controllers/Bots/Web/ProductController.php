<?php

namespace App\Http\Controllers\Bots\Web;

use App\Facades\BotManager;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\BusinessLogic\Methods\Classes\Tinkoff;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductCategoryCollection;
use App\Http\Resources\ProductCategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\BotMenuSlug;
use App\Models\BotUser;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductOption;
use App\Models\Table;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Mpdf\Mpdf;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends Controller
{

    public function sendSBPInvoice(Request $request){
            $request->validate([
                "amount"=>"required",
                "description"=>"required"
            ]);

            BusinessLogic::payment()
                ->setSlug($request->slug ?? null)
                ->setBot($request->bot ?? null)
                ->setBotUser($request->botUser ?? null)
                ->invoiceLink($request->all());
    }

    public function generateTablesQR(Request $request, $domain)
    {

        $bot = Bot::query()
            ->where("bot_domain", $domain)
            ->first();

        if (is_null($bot))
            throw new HttpException(404, "Неверно указан домен бота!");

        $botDomain = $bot->bot_domain;

        $countTables = $request->get("count") ?? 30;
        $scriptId = $request->get("script-id") ?? null;
        $test = $request->get("test") ?? null;

        if (is_null($scriptId))
            throw new HttpException(404, "Скрипт магазина не найдена!");

        $mpdf = new Mpdf();

        $number = Str::uuid();

        $tables = [];
        $row = [];
        for ($i = 0; $i < $countTables; $i++) {

            $qrLink = "https://t.me/$botDomain?start=" .
                base64_encode("777slug" . $scriptId . "table" . $i);

            $qr = (object)[
                "id" => $i + 1,
                "qr" => "https://api.qrserver.com/v1/create-qr-code/?size=450x450&qzone=2&data=$qrLink"
            ];

            if ($i % 2 != 0)
                $row[] = $qr;
            else {
                $tables[] = $row;
                $row = [
                    $qr
                ];
            }
        }

        $tables[] = $row;

        if (!is_null($test))
            return response()
                ->json($tables);

        ini_set('max_execution_time', 30000);
        $mpdf->WriteHTML(view("pdf.tables-qr", [
            "tables" => $tables
        ]));

        return $mpdf->Output("tables-$number.pdf", \Mpdf\Output\Destination::DOWNLOAD);
    }

    public function testSbpTinkoffAutomatic(...$data)
    {
        $bot = BotManager::bot()
            ->getSelf();
        $botUser = BotManager::bot()->currentBotUser();

        $paymentId = $data[2] ?? null;
        $slugId = $data[3] ?? null;

        $slug = BotMenuSlug::query()
            ->find($slugId);

        if (is_null($slug))
            throw new HttpException(404, "Не найден скрипт настройки СБП!");

        $config = $slug->config ?? null;

        if (is_null($config))
            throw new HttpException(400, "Система не настроена!");

        $sbp = Collection::make($config)
            ->where("key", "sbp")
            ->first()["value"] ?? null;

        $terminalKey = $sbp["tinkoff"]["terminal_key"] ?? null;
        $terminalPassword = $sbp["tinkoff"]["terminal_password"] ?? null;
        $tax = $sbp["tinkoff"]["tax"] ?? "osn";
        $vat = $sbp["tinkoff"]["vat"] ?? "vat20";

        $tinkoff = new Tinkoff(config('sbp.payments.tinkoff.url'), $terminalKey, $terminalPassword);

        $state = $tinkoff->getState($paymentId);

        if ($state != "CONFIRMED") {
            BotManager::bot()
                ->reply("Оплата еще не прошла, попробуйте через некоторое время!");
            return;
        }
        $paymentData = $tinkoff->getResponse();

        $paymentId = $paymentData->PaymentId;
        $orderId = $paymentData->OrderId;
        $amount = $paymentData->Amount;

        BotManager::bot()
            ->reply("Оплата клиента в размере $amount руб. прошла успешно!");

    }

    public function changeStatusOrder(Request $request): \Illuminate\Http\Response
    {
        $request->validate([
            "order_id" => "required",
            "status" => "required"
        ]);

        BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->changeStatusOrder(
                $request->order_id ?? null,
                $request->status ?? 0,
                $request->user_telegram_chat_id ?? null
            );

        return response()->noContent();
    }

    /**
     * @throws ValidationException
     */
    public function getDeliveryPrice(Request $request): \Illuminate\Http\JsonResponse
    {

        if (is_null($request->bot ?? null) || is_null($request->slug ?? null))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $request->validate([
            "city" => "required",
            "street" => "required",
            "building" => "required",
        ]);

        $slug = $request->slug ?? null;

        if (is_null($slug))
            return response()->json([
                "distance" => 0,
                "price" => 0
            ]);

        $price_per_km = (Collection::make($slug->config)
            ->where("key", "price_per_km")
            ->first())["value"] ?? 80;

        $min_base_delivery_price = (Collection::make($slug->config)
            ->where("key", "min_base_delivery_price")
            ->first())["value"] ?? 100;


        $geo = BusinessLogic::geo()
            ->setBot($request->bot ?? null)
            ->setSlug($request->slug ?? null)
            ->getCoords([
                "address" => (($request->city ?? "") . ", " . ($request->street ?? "") . ", " . ($request->building ?? ""))
            ]);

        if (($geo->lat ?? 0) > 0 && ($geo->lon ?? 0) > 0) {
            $tmpDistance = BusinessLogic::geo()
                ->setBot($request->bot ?? null)
                ->setSlug($request->slug ?? null)
                ->getDistance($geo->lat ?? 0, $geo->lon ?? 0);

            $distance = $tmpDistance > 0 ? round($tmpDistance / 1000 ?? 0, 2) : 0;
            return response()->json([
                "distance" => $distance,
                "price" => round($min_base_delivery_price + $distance * $price_per_km, 2)
            ]);
        }


        return response()->json([
            "distance" => 0,
            "price" => 0
        ], 400);
    }


    /**
     * @throws ValidationException
     */
    public function getAllOrders(Request $request): \App\Http\Resources\OrderCollection
    {
        return BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->orderList($request->all(), $request->get("size") ?? config('app.results_per_page'), true);

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

    public function notifyUser(Request $request)
    {
        BusinessLogic::review()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->notifyUserForReview($request->all());
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

    /**
     * @throws ValidationException
     */
    public function addCashBackToOrder(Request $request)
    {
        $request->validate([
            "order_id" => "required"
        ]);

        BusinessLogic::delivery()
            ->setBot($request->bot ?? null)
            ->setBotUser($request->botUser ?? null)
            ->addCashBackToOrder($request->all());
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

    public function getProductsByIds(Request $request): ProductCollection
    {
        $request->validate([
            "ids" => "required|array"
        ]);

        return BusinessLogic::products()
            ->byIds($request->ids ?? []);
    }


    public function changeCategoryStatus(Request $request, $categoryId): \App\Http\Resources\ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->changeCategoryStatus($categoryId);
    }

    public function removeCategoryId(Request $request, $categoryId): ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->destroyCategory($categoryId);
    }


    public function listByCategories(Request $request): ProductCategoryCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->listByCategories();
    }

    public function index(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->list(
                $request->search ?? null,
                [
                    "categories" => $request->categories ?? null,
                    "min_price" => $request->min_price ?? null,
                    "max_price" => $request->max_price ?? null
                ],

                $request->get("size") ?? config('app.results_per_page'),
            );
    }

    /**
     * @throws ValidationException
     */
    public function storeCategory(Request $request): ProductCategoryResource
    {
        $request->validate([
            "category" => "required"
        ]);

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->createOrUpdateCategory($request->all());
    }

    public function getCategories(Request $request): ProductCategoryCollection
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->categories(
                true,
                $request->all(),
                $request->get("size") ?? config('app.results_per_page'));
    }

    public function getProduct(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->product($productId);
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
    public function saveProduct(Request $request): ProductResource
    {
        $request->validate([
            "article" => "",
            "title" => "required",
            "description" => "required",
            "type" => "required",
            "current_price" => "required",
        ]);


        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->createOrUpdate($request->all(),
                $request->hasFile('photos') ?
                    $request->file('photos') : null);

    }

    public function removeAllProducts(Request $request)
    {
        BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->removeAllProducts();
    }

    public function destroy(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->destroy($productId);
    }

    public function duplicate(Request $request, $productId): ProductResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->duplicate($productId);
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


    public function getProductsInCategory(Request $request): ProductCollection
    {

        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->productsInCategory(
                $request->category_id ?? null,
                $request->search ?? null
            );
    }

    public function getCategory(Request $request, $categoryId): ProductCategoryResource
    {
        return BusinessLogic::products()
            ->setBot($request->bot ?? null)
            ->category($categoryId);
    }


}
