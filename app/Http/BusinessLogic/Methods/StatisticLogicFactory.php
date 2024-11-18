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


    public function base($startAt, $endAt, $needAll = false, $direction = 'asc', $sortBy = 'price'): array
    {
        // Проверка условий
        $this->validateBotAndUser();

        // Форматирование временных рамок
        [$startOfMonth, $endOfMonth] = $this->getDateRange($startAt, $endAt, $needAll);

        $botId = $this->bot->id;

        // Получение заказов
        $orders = Order::query()
            ->where("bot_id", $botId)
            ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
            ->orderBy($sortBy, $direction)
            ->get();

        // Обработка заказов
        $productsData = $this->processOrders($orders);

        // Суммарные запросы
        $summaryData = $this->getSummaryData($botId, $startOfMonth, $endOfMonth);

        return array_merge([
            "start_at" => $startOfMonth,
            "end_at" => $endOfMonth,
            "users_in_bd" => $this->getUsersCount($botId, $startOfMonth, $endOfMonth),
        ], $productsData, $summaryData);
    }

    private function validateBotAndUser()
    {
        if (is_null($this->bot) || is_null($this->botUser)) {
            throw new HttpException(403, "Не выполнены условия функции");
        }

        if (!$this->botUser->is_admin) {
            throw new HttpException(403, "Пользователь не является администратором");
        }
    }

    private function getDateRange($startAt, $endAt, $needAll): array
    {
        return [
            ($needAll ? Carbon::now()->startOfMillennium() : Carbon::parse($startAt))->format("Y-m-d H:i:s"),
            ($needAll ? Carbon::now()->endOfMillennium() : Carbon::parse($endAt))->format("Y-m-d H:i:s"),
        ];
    }

    private function processOrders($orders): array
    {
        $products = [];
        $totalCount = 0;
        $totalPrice = 0;

        foreach ($orders as $order) {
            $details = $order->product_details ?? [];
            if (empty($details)) continue;

            $orderProducts = $details[0]["products"] ?? [];
            if (!is_array($orderProducts)) continue;

            foreach ($orderProducts as $product) {
                $product = (object) $product;

                if (!isset($product->title)) continue;

                $title = $product->title;
                $count = $product->count ?? 1;
                $price = $product->price ?? 0;

                if (!isset($products[$title])) {
                    $products[$title] = (object) ["count" => 0, "price" => 0];
                }

                $products[$title]->count += $count;
                $products[$title]->price += $price;

                $totalCount += $count;
                $totalPrice += $price;
            }
        }

        $productsCollection = collect($products)->map(function ($item, $key) use ($totalCount, $totalPrice) {
            return (object) [
                "title" => $key,
                "count" => $item->count,
                "price" => $item->price,
                "volume_count_ratio" => round(($item->count / $totalCount) * 100, 2),
                "volume_price_ratio" => round(($item->price / $totalPrice) * 100, 2),
            ];
        });

        return [
            'orders' => [
                'products' => $productsCollection->values()->all(),
                'total_count' => $totalCount,
                'total_price' => $totalPrice,
            ]
        ];
    }

    private function getSummaryData($botId, $startOfMonth, $endOfMonth): array
    {
        $sumOrders = $this->getGroupedSum('orders', 'summary_price', $botId, $startOfMonth, $endOfMonth);
        $usersByPeriod = $this->getGroupedCount('bot_users', $botId, $startOfMonth, $endOfMonth);
        $cashBackUp = $this->getGroupedSum('cash_back_histories', 'amount', $botId, $startOfMonth, $endOfMonth, ['operation_type' => 1]);
        $cashBackDown = $this->getGroupedSum('cash_back_histories', 'amount', $botId, $startOfMonth, $endOfMonth, ['operation_type' => 0]);

        return [
            'summary_orders' => $sumOrders,
            'users' => $usersByPeriod,
            'cashback_up' => $cashBackUp,
            'cashback_down' => $cashBackDown,
        ];
    }

    private function getGroupedSum($table, $field, $botId, $startOfMonth, $endOfMonth, $additionalConditions = []): array
    {
        $query = DB::table($table)
            ->selectRaw("SUM(`$field`) as sum, MONTH(`created_at`) as month, YEAR(`created_at`) as year")
            ->where('bot_id', $botId)
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupByRaw("MONTH(`created_at`), YEAR(`created_at`)");

        foreach ($additionalConditions as $key => $value) {
            $query->where($key, $value);
        }

        return $query->get()->toArray();
    }

    private function getUsersCount($botId, $startOfMonth, $endOfMonth): int
    {
        return BotUser::query()
            ->where("bot_id", $botId)
            ->whereBetween("created_at", [$startOfMonth, $endOfMonth])
            ->count();
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
