<?php

namespace App\Http\BusinessLogic\Methods;

use App\Enums\OrderStatusEnum;
use App\Enums\OrderTypeEnum;
use App\Exports\BookingsExport;
use App\Facades\BotMethods;
use App\Facades\BusinessLogic;
use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\BasketCollection;
use App\Http\Resources\BasketResource;
use App\Http\Resources\BotUserCollection;
use App\Http\Resources\BotUserResource;
use App\Http\Resources\TableCollection;
use App\Http\Resources\TableResource;
use App\Models\AmoCrm;
use App\Models\Basket;
use App\Models\Bot;
use App\Models\Order;
use App\Models\Table;
use Carbon\Carbon;
use Faker\Provider\Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Telegram\Bot\FileUpload\InputFile;

class TableLogicFactory extends BaseLogicFactory
{

    public function sendOrderToChat($tableId): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant", "clients"])
            ->where("bot_id", $this->bot->id)
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Ошибка выбора столика!");

        $basket = \App\Models\Basket::query()
            ->where("bot_id", $this->bot->id)
            ->where("table_id", $tableId)
            ->whereNull("ordered_at")
            ->get();

        $summaryPrice = 0;
        $summaryCount = 0;
        $description = "Ваш столик <b>№$table->number</b>. Ваш текущий заказ состоит из:\n\n<b>Основной заказ</b>:\n";

        foreach ($basket as $basketItem) {
            $product = $basketItem->product ?? null;
            $collection = $basketItem->collection ?? null;
            $count = $basketItem->count ?? 0;
            $price = 0;

            if (!is_null($product)) {
                $price = $product->current_price ?? 0;//* $count;
                $description .= "$product->title x$count = $price руб.,\n";
                $price = $price * $count;
            }

            if (!is_null($collection)) {
                $collectionTitles = "";

                $params = is_null($item->params ?? null) ? null : (object)$basketItem->params;

                foreach (($collection->products ?? []) as $basketProduct) {
                    if (!in_array($basketProduct->id, $params->ids ?? []))
                        continue;

                    $collectionTitles .= "-" . $basketProduct->title . "\n";
                    $price += $product->current_price ?? 0;
                }

                $description .= "Коллекция $collection->title x$count = $price руб.,\n";
                $price = $price * $basketItem->count;
            }


            $summaryCount += $count;
            $summaryPrice += $price;
        }

        $additionalServices = $table->additional_services ?? [];

        if (count($additionalServices) > 0) {
            $description .= "\n<b>Дополнительные платные сервисы:</b>\n";
            foreach ($additionalServices as $serviceItem) {
                $serviceItem = (object)$serviceItem;
                $price = $serviceItem->price ?? 0;//* $count;
                $description .= "$serviceItem->title x1 = $price,\n";
                $summaryCount += 1;
                $summaryPrice += $price;
            }
        }

        $description .= "\nИтого: <b>$summaryPrice руб.</b>";

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                $description
            );
    }

    public function storeAdditionalService($tableId, $services)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant", "clients"])
            ->where("bot_id", $this->bot->id)
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Ошибка выбора столика!");

        $table->additional_services = $services ?? [];
        $table->save();

        $table->refresh();

        return new TableResource($table);
    }

    public function changeBasketStatus($tableId, $type = 0)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");


        $baskets = Basket::query()
            ->with(["collection", "product"])
            ->where("table_id", $tableId)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->get();

        foreach ($baskets as $basket) {
            $basket->table_approved_at = $type == 0 ? Carbon::now() : null;
            $basket->save();
        }

        return new BasketCollection($baskets);
    }

    public function changeProductStatus($basketId)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");


        $basket = Basket::query()
            ->with(["collection", "product"])
            ->where("id", $basketId)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->first();

        $basket->table_approved_at = is_null($basket->table_approved_at) ? Carbon::now() : null;
        $basket->save();

        return new BasketResource($basket);
    }

    /**
     * @throws ValidationException
     */
    public function tablePay(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Не все параметры функции заданы!");


        $validator = Validator::make($data, [
            "table_id" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);


        $tableId = $data["table_id"];

        $table = Table::query()
            ->with(["creator", "officiant", "clients"])
            ->where("bot_id", $this->bot->id)
            ->where("id", $tableId)
            ->first();

        return BusinessLogic::payment()
            ->setBot($this->bot)
            ->setBotUser($this->botUser)
            ->setSlug($this->slug)
            ->sbpTablePayment($data, $table);
    }

    public function getFullTableData($tableId): object
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant", "clients"])
            ->where("bot_id", $this->bot->id)
            ->where("id", $tableId)
            ->first();

        $baskets = Basket::query()
            ->with(["collection", "product", "botUser"])
            ->where("table_id", $tableId)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->get();


        $clientBaskets = [];
        foreach ($table->clients as $client) {
            $clientBaskets[] = (object)[
                "id" => $client->id ?? null,
                "name" => $client->name ?? $client->fio_from_telegram ?? '-',
                "summary_price" => 0,
                "summary_count" => 0,
                "basket" => [],
            ];
        }


        $allSummaryPrice = 0;
        $allSummaryCount = 0;
        foreach ($baskets as $basket) {
            foreach ($clientBaskets as $clientBasket) {
                if ($clientBasket->id == $basket->bot_user_id) {
                    $product = (object)$basket->product;
                    $clientBasket->summary_count += $basket->count;

                    $price = ($product->current_price ?? 0) * $basket->count;
                    $clientBasket->summary_price += $price;
                    $allSummaryPrice += $price;
                    $allSummaryCount += $basket->count;
                    $clientBasket->basket[] = new BasketResource($basket);
                }
            }
        }


        return (object)[
            "summary_price" => $allSummaryPrice,
            "summary_count" => $allSummaryCount,
            "table" => new TableResource($table),
            "clients" => BotUserResource::collection($table->clients ?? null),
            "basket" => $clientBaskets
        ];
    }

    public function changeTableWaiter($tableId)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant", "clients"])
            ->where("bot_id", $this->bot->id)
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Ошибка выбора столика!");

        $table->officiant_id = is_null($table->officiant_id) ? $this->botUser->id : null;
        $table->save();

        $table->refresh();

        return new TableResource($table);
    }

    public function waiterTableList($size = null): TableCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $size = $size ?? config('app.results_per_page');

        $tables = Table::query()
            ->with(["creator"])
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->where(function ($query) {
                $query->where('officiant_id', $this->botUser->id)
                    ->orWhereNull("officiant_id");
            })
            ->orderBy("id", "asc")
            ->paginate($size);

        return new TableCollection($tables);
    }

    public function approvedSelfBasket(): BasketCollection
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $allProductsInBasket = Basket::query()
            ->with(["collection", "product"])
            ->where("bot_user_id", $this->botUser->id)
            ->where("bot_id", $this->bot->id)
            ->whereNull("ordered_at")
            ->whereNotNull("table_approved_at")
            ->get();

        return new BasketCollection($allProductsInBasket);

    }

    /**
     * @throws HttpException
     */
    public function current($tableId = null): object
    {
        if (is_null($this->bot))
            throw new HttpException(404, "Бот не найден!");


        $tableWithClient = is_null($tableId) ?
            Table::query()
                ->with(["creator", "officiant"])
                ->where("bot_id", $this->bot->id)
                ->whereNull("closed_at")
                ->whereHas('clients', function ($query) {
                    $query->where('id', $this->botUser->id);
                })->first() :
            Table::query()
                ->with(["creator", "officiant"])
                ->where("bot_id", $this->bot->id)
                ->where("id", $tableId)
                ->first();


        if (is_null($tableWithClient))
            throw new HttpException(404, "Увы, вы не заняли ни один из столиков!");

        return $this->getFullTableData($tableWithClient->id);
    }

    public function allOrders()
    {

    }

    public function callWaiter($tableId, $needPayment = false): void
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры заданы!");

        $table = Table::query()
            ->with(["creator", "officiant"])
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Столик в данный момент не занят!");

        $tableNumber = $table->number ?? null;

        if (is_null($table->officiant_id ?? null)) {
            $thread = $this->bot->topics["orders"] ?? null;

            $botDomain = $this->bot->bot_domain;

            $chatId = $this->botUser->telegram_chat_id;

            $link = "https://t.me/$botDomain?start=" .
                base64_encode("001" . $chatId . "table$tableNumber");

            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendInlineKeyboard(
                    $this->bot->order_channel,
                    "Клиент ждет официанта за столиком №" . ($tableNumber + 1) . ". Официант еще не назначен!",
                    [
                        [
                            ["text" => "🍽️Взять в работу", "url" => $link],
                        ]
                    ],
                    $thread
                );

        } else {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $table->officiant->telegram_chat_id,
                    "Клиент ждет вас за столиком №" . ($tableNumber + 1) . "!" . ($needPayment ? "Клиент просит принести счет" : ""),
                );
        }

    }

    public function requestApproveTable($tableId): void
    {
        if (is_null($this->bot) || is_null($this->botUser) || is_null($this->slug))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant"])
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Столик в данный момент не занят!");

        $path = env("APP_URL") . "/bot-client/simple/%s?slug=%s&hide_menu#/s/admin/tables-manager/" . $table->id;

        $keyboard = [
            [
                ["text" => "Перейти к столику",
                    "web_app" => [
                        "url" => sprintf(
                            $path,
                            $this->bot->bot_domain,
                            $this->slug->id ?? 'route'
                        )
                    ]
                ],
            ]
        ];
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                is_null($table->officiant) ? $this->bot->order_channel : $table->officiant->telegram_chat_id,
                "Один из клиентов за столиком №" . ($table->number + 1) . " сделал заказ и просит вас подтвердить его!",
                !is_null($table->officiant) ? $keyboard : []
            );

        http://localhost:8000/bot-client/simple/nextitgroup_bot?slug=2606#/s/admin/tables-manager/1
    }

    public function closeTable($tableId)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $table = Table::query()
            ->with(["creator", "officiant"])
            ->where("bot_id", $this->bot->id)
            ->whereNull("closed_at")
            ->where("id", $tableId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Столик в данный момент не занят!");

        if (is_null($table->officiant_id ?? null))
            throw new HttpException(404, "В данный момент у столика нет официанта!");

        if (!is_null($table->closed_at ?? null))
            throw new HttpException(400, "Столик уже закрыт!");

        $table->closed_at = Carbon::now();
        $table->save();

        $basket = Basket::query()
            ->where("table_id", $table->id)
            ->get();

        foreach ($basket as $basketItem) {
            $basketItem->ordered_at = Carbon::now();
            $basketItem->save();
        }

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendInlineKeyboard(
                $table->creator->telegram_chat_id,
                "Спасибо за Ваш визит, ждем с нетерпением ещё! Пожалуйста, поставьте оценку нашей работе!", [
                    [
                        ["text" => "😡", "callback_data" => "/send_review 0"],
                        ["text" => "😕", "callback_data" => "/send_review 1"],
                        ["text" => "😐", "callback_data" => "/send_review 2"],
                        ["text" => "🙂", "callback_data" => "/send_review 3"],
                        ["text" => "😁", "callback_data" => "/send_review 4"],
                    ]
                ]
            );
    }

    /**
     * @throws ValidationException
     */
    public function nearestBookingList(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $start = is_null($data['start_date'] ?? null) ? Carbon::now() : Carbon::parse($data['start_date']); // например "2025-11-01"
        $end = is_null($data['end_date'] ?? null) ? Carbon::now()->addWeek() : Carbon::parse($data['end_date']);   // например "2025-11-30

        // получаем все брони за период (без groupBy)
        $bookings = Table::query()
            ->whereBetween('booked_date_at', [$start, $end])
            ->where('booked_date_at', '>=', now()->toDateString())
            ->orderBy('booked_date_at')
            ->get();


// группируем и считаем количество броней на день через PHP
        $bookingCounts = $bookings->groupBy('booked_date_at')->map(function ($items, $date) {
            return [
                'date' => $date,
                'total' => $items->count(),
            ];
        });


        $bookings = Table::query()
            ->whereBetween('booked_date_at', [$start, $end])
            ->where('booked_date_at', '>=', now()->toDateString())
            ->orderBy('booked_date_at')
            ->get();

        return [
            "counts" => $bookingCounts,
            "bookings" => new TableCollection($bookings)
        ];
    }

    public function myUpcomingBookings()
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $now = Carbon::now();

        $tables = Table::query()
            ->where('creator_id', $this->botUser->id)
            ->whereRaw("
                STR_TO_DATE(CONCAT(booked_date_at, ' ', booked_time_at), '%Y-%m-%d %H:%i:%s') >= ?
            ", [$now])
            ->orderBy('booked_date_at')
            ->orderBy('booked_time_at')
            ->get();

        return new TableCollection($tables);
    }

    public function bookingList($tableNumber, $date = null)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $now = !is_null($date) ? Carbon::parse($date) : Carbon::now();

        $tables = Table::query()
            ->where('number', $tableNumber)
            ->whereRaw("STR_TO_DATE(CONCAT(booked_date_at, ' ', booked_time_at), '%Y-%m-%d %H:%i:%s') >= ?", [$now])
            ->get();

        return new TableCollection($tables);
    }

    /**
     * @throws ValidationException
     */
    public function exportNearestBookings(array $data)
    {

        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");


        $validator = Validator::make($data, [
            "start_date" => "required",
            "end_date" => "required",

        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $start = Carbon::parse($data['start_date']);
        $end = Carbon::parse($data['end_date']);

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendDocument(
                $this->botUser->telegram_chat_id,
                "Выгрузка броней за период",
                InputFile::createFromContents(
                    Excel::raw(new BookingsExport($start, $end), \Maatwebsite\Excel\Excel::XLSX),
                    "Бронирование столиков.xlsx"
                )
            );
    }

    /**
     * @throws ValidationException
     * @throws \HttpException
     */
    public function bookATable(array $data)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");

        $validator = Validator::make($data, [
            "name" => "required",
            "phone" => "required",
            "persons" => "required",
            "description" => "required",
            "table" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator);

        $date = $data["date"];
        $time = $data["time"];

        $bookedAt = Carbon::parse($date . ' ' . $time);

        $table = json_decode($data["table"]);
        $number = $table->number;
        $persons = $data["persons"];

        $exists = Table::query()
            ->where('number', $number)
            ->get()
            ->filter(fn($table) => $table->booked_at->between($bookedAt, $bookedAt->addHours(2)))
            ->isNotEmpty();

        if ($exists) {
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $this->botUser->telegram_chat_id,
                    "Ваш столик #$number к сожалению занят $date в $time! С вами свяжется администратор для уточнения информации.");

            sleep(1);
            $thread = $this->bot->topics["callback"] ?? null;
            BotMethods::bot()
                ->whereBot($this->bot)
                ->sendMessage(
                    $this->bot->order_channel,
                    "Пользователь хотел забронировать столик #$number но он к сожалению занят $date в $time! Свяжитесь с пользователем для уточнения информации!"
                    . "\n\n<a href='tg://user?id=" . $this->botUser->telegram_chat_id . "'>Перейти к чату с пользователем</a>\n",
                    $thread
                );
            throw new \HttpException("На данное время уже есть бронь!", 400);
        }

        $table = Table::query()
            ->create([
                "bot_id" => $this->bot->id,
                'creator_id' => $this->botUser->id,
                'number' => $number,
                "booked_date_at" => $date,
                "booked_time_at" => $time,
                "booked_info" => (object)[
                    "name" => $data["name"],
                    "phone" => $data["phone"],
                    "persons" => $persons,
                    "description" => $data["description"],
                    "table" => $data["table"],
                ],
            ]);
        $table->clients()->sync($this->botUser->id);
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Ваш столик #$number успешно забронирован на $date в $time на $persons чел.! "
            );
        sleep(1);
        $thread = $this->bot->topics["orders"] ?? null;
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->bot->order_channel,
                "Пользователь забронировал столик #$number на $date в $time на $persons чел!"
                . "\n\n<a href='tg://user?id=" . $this->botUser->telegram_chat_id . "'>Перейти к чату с пользователем</a>\n",
                $thread
            );
        return new TableResource($table);
    }


    /**
     * @throws ValidationException
     * @throws \HttpException
     */
    public function cancelBookingTable($bookingId)
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(404, "Не все параметры функции заданы!");


        $table = Table::query()
            ->where('id', $bookingId)
            ->first();

        if (is_null($table))
            throw new HttpException(404, "Бронь не найдена!");

        $baskets = Basket::query()
            ->where("table_id", $table->id)
            ->get();

        foreach ($baskets as $basket)
            $basket->delete();

        $table->clients()->detach();

        $number = $table->number;
        $date = $table->booked_date_at;
        $time = $table->booked_time_at;

        $table->delete();

        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->botUser->telegram_chat_id,
                "Вы отменили бронь на столик #$number на дату $date $time.");

        sleep(1);
        $thread = $this->bot->topics["callback"] ?? null;
        BotMethods::bot()
            ->whereBot($this->bot)
            ->sendMessage(
                $this->bot->order_channel,
                "Пользователь отменил бронирование столика #$number на $date в $time! Свяжитесь с пользователем для уточнения информации!"
                . "\n\n<a href='tg://user?id=" . $this->botUser->telegram_chat_id . "'>Перейти к чату с пользователем</a>\n",
                $thread
            );
    }
}
