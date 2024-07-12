<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Facades\BotMethods;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\OrderCollection;
use App\Http\Resources\ProductCollection;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\Documents;
use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\Objects\Document;


class DeliveryLogicFactory
{
    protected $bot;
    protected $botUser;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;

    }

    public function setBot($bot): static
    {
        if (is_null($bot))
            throw new HttpException(400, "Бот не задан!");

        $this->bot = $bot;
        return $this;
    }

    public function setBotUser($botUser): static
    {
        if (is_null($botUser))
            throw new HttpException(400, "Пользователь бота не задан!");

        $this->botUser = $botUser;
        return $this;
    }

    /**
     * @throws ValidationException
     */
    public function registerDeliveryman(array $data): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "documents.*.title" => "required",
            "documents.*.description" => "required",
            "documents.*.type" => "required",
            "documents.*.file_id" => "required",
            "documents.*.params" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $botUser = $this->botUser;

        $birthday = Carbon::parse($data["birthday"] ?? $botUser->birthday ?? Carbon::now())->format("Y-m-d");

        $botUser->name = $data["name"] ?? $botUser->name ?? null;
        $botUser->phone = $data["phone"] ?? $botUser->phone ?? null;
        $botUser->email = $data["email"] ?? $botUser->email ?? null;
        $botUser->birthday = $birthday;
        $botUser->city = $data["city"] ?? $botUser->city ?? null;
        $botUser->country = $data["country"] ?? $botUser->country ?? null;
        $botUser->address = $data["address"] ?? $botUser->address ?? null;
        $botUser->sex = (bool)(($data["sex"] ?? false));
        $botUser->age = Carbon::now()->year - Carbon::parse($birthday)
                ->year;
        $botUser->save();

        $message = sprintf("Ф.И.О: %s\nТелефон: %s\nПочта: %s\nДР: %s\nВозраст: %s\nСтрана: %s\nГород: %s\nАдрес: %s\nПол: %s",
            $botUser->name ?? "Не указано",
            $botUser->phone ?? "Не указано",
            $botUser->email ?? "Не указано",
            $botUser->birthday ?? "Не указано",
            $botUser->age ?? "Не указано",
            $botUser->country ?? "Не указано",
            $botUser->city ?? "Не указано",
            $botUser->address ?? "Не указано",
            $botUser->sex ? "муж" : "жен",

        );

        $documents = $data["documents"] ?? [];

        $tmpDocumentInfo = "";
        $keyboard = [];

        foreach ($documents as $document) {
            $document = (object)$document;
            $doc = Documents::query()->updateOrCreate(
                [
                    'bot_id' => $this->bot->id,
                    'bot_user_id' => $botUser->id,
                    'file_id' => $document->file_id,
                ],
                [
                    'title' => $document->title ?? null,
                    'description' => $document->description ?? null,
                    'type' => $document->type ?? 0,
                    'params' => json_decode($document->params ?? '[]'),
                    'verified_at' => null,
                ]);

            $keyboard[] = [
                [
                    "text" => $doc->title ?? $doc->id ?? '-', "callback_data" => "/show_document $doc->id"
                ]
            ];
            $tmpDocumentInfo .= sprintf("<b>%s</b>\n<em>%s</em>", $doc->title ?? '-', $doc->description ?? '-');
        }

        $thread = $bot->topics["questions"] ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $this->bot->order_channel ??  null,
                "#регистрация_доставщика\n$message\n",
                $keyboard,
                $thread
            )
            ->sendMessage(
                $botUser->telegram_chat_id,
                "Ваша заявка на позицию доставщика принята на рассмотрение!"
            );
    }

    /**
     * @throws HttpException
     */
    public function storeCoordsToOrder(float $lat = 0, float $lon = 0): bool
    {
        //сохраняем координаты доставщика в заказ
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $orders = Order::query()
            ->where('bot_id', $this->bot->id)
            ->where('deliveryman_id', $this->botUser->id)
            ->whereNot('status', OrderStatusEnum::Completed->value)
            ->get() ?? [];

        if (count($orders) == 0)
            return false;

        foreach ($orders as $order) {
            $order->deliveryman_latitude = $lat;
            $order->deliveryman_longitude = $lon;
            $order->save();
        }

        return true;
    }

    public function userOrders(){

    }

    ///необходимо оповещать всех доставщиков, которые в работе когда заказ появился и когда заказ принят \ отменен
    public function acceptOrder($orderId): bool
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $order = Order::query()
            ->find($orderId);

        if (is_null($order))
            return false;

        $documents = Documents::query()
            ->where("bot_id", $this->bot->id)
            ->where("bot_user_id", $this->botUser->id)
            ->whereNotNull("verified_at")
            ->get();

        $deliverymanInfo = (object)[
            "name" => $this->botUser->name ?? $this->botUser->fio_from_telegram ?? $this->botUser->telegram_chat_id,
            "phone" => $this->botUser->phone ?? '-',
            "documents" => []
        ];

        foreach ($documents as $document) {
            $deliverymanInfo->documents[] = $document->params;
        }

        $order->update([
            'deliveryman_id' => $this->botUser->id,
            'delivery_service_info' => $this->bot->title ?? $this->bot->bot_domain ?? $this->bot->id,
            'deliveryman_info' => json_decode($deliverymanInfo),
            'delivery_price' => 0,
            'delivery_range' => 0,
            'status' => OrderStatusEnum::InDelivery->value,
        ]);

        $thread = $bot->topics["questions"] ?? null;

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->bot->order_channel ??  null,
                "#заказ_в_работе\n№$order->id взят в работу доставщиком $deliverymanInfo->name ($deliverymanInfo->phone)",
                $thread
            );

        return true;
    }

    public function confirmDelivery($orderId)
    {
        //подтвердить можно в случае если координаты доставщика и заказчика близко друг к другу
        //может быть вести какой-то лог
        //выставить рейтинг
    }

    public function declineOrder($orderId)
    {
        //отмена заказа доставщиком и возврат в активные заказы
    }

    /**
     * @throws HttpException
     * @throws ValidationException
     */
    public function repeatOrder(array $data): ProductCollection
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $validator = Validator::make($data, [
            "products" => "required",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $products = Product::query()
            ->where("bot_id", $this->bot->id)
            ->whereNull("in_stop_list_at")
            ->whereIn("title", $data["products"])
            ->get();

        return new ProductCollection($products);

    }

    public function orderList($size = 30): OrderCollection
    {
        //список заказов с фильтром: мои заказы, все заказы, архивные заказы (статус доставлено)

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Бот не найден!");

        $orders = Order::query()
            ->where("bot_id", $this->bot->id)
            ->where("customer_id", $this->botUser->id)
            ->orderBy("created_at", "desc")
            ->paginate($size);

        return new OrderCollection($orders);
        /*    $order = Order::query()
                ->find($orderId);

            if (is_null($order))
                return false;*/

    }

    public function printOrderToPdf()
    {


    }

    public function printStatiticToExcel()
    {
        //генерация xls-документа со статистикой по доставщику
    }

    public function globalStatistic()
    {
        //глобальная статистика доставки
    }

    public function personalStatistic()
    {
        //персональная статистика доставщика
    }

    public function removeOrder()
    {
        //удаление заказа владельцем заказа
    }

}
