<?php

namespace Database\Seeders;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Order::factory()->count(5)->create();

        for ($i = 0; $i < 10; $i++)
            Order::query()->create([
                'status' => OrderStatusEnum::NewOrder,
                'need_delivery' => true,
                'delivery_address' => "test address $i",
                'comment' => "test comment $i",
                'summary_price' => 1000,
                'deliveryman_id' => null,
                'payed_at' => Carbon::now()
            ]);

        Order::query()->create([
            'status' => OrderStatusEnum::Completed,
            'need_delivery' => false,
            'delivery_address' => "test address 2",
            'comment' => "test comment 2",
            'summary_price' => 1500,
            'deliveryman_id' => null,
            'payed_at' => Carbon::now()
        ]);


    }
}
