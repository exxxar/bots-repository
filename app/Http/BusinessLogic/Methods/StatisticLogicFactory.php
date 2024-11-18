<?php

namespace App\Http\BusinessLogic\Methods;

use App\Http\Resources\AmoCrmResource;
use App\Http\Resources\TrafficSourceCollection;
use App\Models\AmoCrm;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\CashBack;
use App\Models\CashBackHistory;
use App\Models\Order;
use App\Models\TrafficSource;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class StatisticLogicFactory
{
    protected $bot;
    protected $botUser;
    protected $slug;

    public function __construct()
    {
        $this->bot = null;
        $this->botUser = null;
        $this->slug = null;


    }

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
    public function setSlug($slug = null): static
    {
        if (is_null($slug))
            throw new HttpException(400, "Команда не задана!");

        $this->slug = $slug;
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
     * @throws HttpException
     */
    public function base($startAt, $endAt, $needAll = false, $direction = 'asc', $sortBy = 'price'): array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        if (!$this->botUser->is_admin)
            throw new HttpException(403, "Пользователь не является администратором");


        $startOfMonth = ($needAll ?
            Carbon::now()->startOfMillennium() :
            Carbon::parse($startAt))
            ->format("Y-m-d H:i:s");
        $endOfMonth = ($needAll ?
            Carbon::now()->endOfMillennium() :
            Carbon::parse($endAt))
            ->format("Y-m-d H:i:s");

        $botId = $this->bot->id;

        $orders = Order::query()
            ->where("bot_id", $botId)
            ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
            ->orderBy("created_at", "desc")
            ->get();

        $tmp = [];

        $tmpSummaryCount = 0;
        $tmpSummaryPrice = 0;

        foreach ($orders as $order) {
            $details = $order->product_details ?? [];

            if (count($details) == 0)
                continue;

            $products = $details[0]["products"] ?? null;

            if (is_null($products))
                continue;

            if (is_array($products))
                foreach ($products as $product) {
                    $product = (object)$product;
                    if (!is_null($product->title ?? null)) {
                        $tmp[$product->title] = !isset($tmp[$product->title]) ?
                            (object)[
                                "count" => $product->count ?? 1,
                                "price" => $product->price ?? 0,
                            ] :
                            (object)[
                                "count" => $tmp[$product->title]->count + ($product->count ?? 1),
                                "price" => $tmp[$product->title]->price + ($product->price ?? 0),
                            ];

                        $tmpSummaryCount += $product->count ?? 0;
                        $tmpSummaryPrice += $product->price ?? 0;
                    }

                }
        }

        $tmpConvertedOrders = [];

        foreach ($tmp as $key => $item) {
            $tmpConvertedOrders[] = (object)[
                "title" => $key,
                "count" => $item->count ?? 0,
                "price" => $item->price ?? 0,
                "volume_count_ratio" => round(($item->count / $tmpSummaryCount) * 100, 2),
                "volume_price_ratio" => round(($item->price / $tmpSummaryPrice) * 100, 2),
            ];

        }

        $tmpConvertedOrders = Collection::make($tmpConvertedOrders);

        if ($direction == 'desc')
            $tmpConvertedOrders = $tmpConvertedOrders
                ->sortByDesc($sortBy);
        if ($direction == 'asc')
            $tmpConvertedOrders = $tmpConvertedOrders
                ->sortBy($sortBy);

        $tmpConvertedOrders = $tmpConvertedOrders
            ->values()->all();


        $sumOrders = DB::query()
            ->select(DB::raw("SUM(`summary_price`) as sump,MONTH(`created_at`) as m, YEAR(`created_at`) as y FROM `orders` WHERE `bot_id`=$botId
            and `created_at` BETWEEN '$startOfMonth' AND '$endOfMonth'
GROUP BY MONTH(`created_at`), YEAR(`created_at`)
ORDER  BY MONTH(`created_at`) ASC"))->get();

        $usersByPeriod = DB::query()
            ->select(DB::raw("COUNT(`id`) as count, MONTH(`created_at`) as m, YEAR(`created_at`) as y FROM `bot_users` WHERE `bot_id`=$botId
            and `created_at` BETWEEN '$startOfMonth' AND '$endOfMonth'
GROUP BY MONTH(`created_at`), YEAR(`created_at`)
ORDER  BY MONTH(`created_at`) ASC"))->get();

        $cashBackUp = DB::query()
            ->select(DB::raw("SUM(`amount`) as sum, MONTH(`created_at`) as m, YEAR(`created_at`) as y FROM `cash_back_histories` WHERE `bot_id`=$botId
            and `operation_type`=1
            and `created_at` BETWEEN '$startOfMonth' AND '$endOfMonth'
GROUP BY MONTH(`created_at`), YEAR(`created_at`)
ORDER  BY MONTH(`created_at`) ASC"))->get();

        $cashBackDown = DB::query()
            ->select(DB::raw("SUM(`amount`) as sum, MONTH(`created_at`) as m, YEAR(`created_at`) as y FROM `cash_back_histories` WHERE `bot_id`=$botId
            and `operation_type`=0
            and `created_at` BETWEEN '$startOfMonth' AND '$endOfMonth'
GROUP BY MONTH(`created_at`), YEAR(`created_at`)
ORDER  BY MONTH(`created_at`) ASC"))->get();

        return [
            "start_at" => $startOfMonth,
            "end_at" => $endOfMonth,
            "users_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            'orders' => (object)[
                "sum" => $sumOrders,
                "products" => $tmpConvertedOrders,

                "count_products" => Order::query()
                    //  ->where("bot_id")
                    ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                    ->sum("product_count"),
                "count_orders" => Order::query()
                    //  ->where("bot_id")
                    ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                    ->count(),
            ],
            'users' => (object)[
                "sum" => $usersByPeriod,
            ],
            'cashback_up' => (object)[
                "sum" => $cashBackUp,
            ],
            'cashback_down' => (object)[
                "sum" => $cashBackDown,
            ],

            "vip_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_vip", true)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),

            "admin_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "work_admin_in_bd" => BotUser::query()
                ->where("bot_id", $this->bot->id)
                ->where("is_admin", true)
                ->where("is_work", true)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "summary_cashback" => CashBack::query()
                ->where("bot_id", $this->bot->id)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "summary_cashback_people_count" => CashBack::query()
                ->where("bot_id", $this->bot->id)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),

            "cashback_summary_up" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "cashback_up_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "cashback_up_level_1" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "cashback_up_level_1_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 1)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "cashback_up_level_2" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 2)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "cashback_up_level_2_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 2)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "cashback_up_level_3" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 3)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "cashback_up_level_3_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("level", 3)
                ->where("operation_type", 1)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count(),
            "cashback_summary_down" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->sum("amount"),
            "cashback_down_people_count" => CashBackHistory::query()
                ->where("bot_id", $this->bot->id)
                ->where("operation_type", 0)
                ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                ->count()
        ];
    }

    /**
     * @throws HttpException
     */
    public function traffic($startAt, $endAt, $needAll = false, $direction = 'desc', $sortBy = 'created_at'): array
    {
        if (is_null($this->bot) || is_null($this->botUser))
            throw new HttpException(403, "Не выполнены условия функции");

        if (!$this->botUser->is_admin)
            throw new HttpException(403, "Пользователь не является администратором");


        $startOfMonth = ($needAll ?
            Carbon::now()->startOfMillennium() :
            Carbon::parse($startAt))
            ->format("Y-m-d H:i:s");
        $endOfMonth = ($needAll ?
            Carbon::now()->endOfMillennium() :
            Carbon::parse($endAt))
            ->format("Y-m-d H:i:s");

        $botId = $this->bot->id;

        /*     $traffics = TrafficSource::query()
                 ->where("bot_id", $botId)
                 ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
                 ->orderBy($sortBy, $direction)
                 ->get();*/


        $result = DB::query()
            ->select(DB::raw("COUNT(DISTINCT `source`) as count, `source`"))
            ->from('traffic_sources')
            ->where('bot_id', '=', $botId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('source')
            ->orderBy('source', 'asc')
            ->get();

        $result = Collection::make($result);

        if ($direction == 'desc')
            $result = $result
                ->sortByDesc($sortBy);
        if ($direction == 'asc')
            $result = $result
                ->sortBy($sortBy);

        return $result
            ->values()
            ->all();
    }

}
