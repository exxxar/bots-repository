<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::query()
            ->where('slug',"obedygo")
            ->first();

        Location::query()->create([
            'images'=>null,
            'lat'=>0,
            'lon'=>0,
            'address'=>"г. Донецк, ул. Университетская, 24",
            'description'=>"тест",
            'location_channel'=>"1234",
            'company_id'=>$company->id,
            'is_active'=>true,
            'can_booking'=>true,
        ]);

        Location::query()->create([
            'images'=>null,
            'lat'=>0,
            'lon'=>0,
            'address'=>"г. Донецк, ул. Артема, 25",
            'description'=>"тест",
            'location_channel'=>"1234",
            'company_id'=>$company->id,
            'is_active'=>true,
            'can_booking'=>true,
        ]);

        Location::query()->create([
            'images'=>null,
            'lat'=>0,
            'lon'=>0,
            'address'=>"г. Донецк, ул. Красноармейская, 34 строение 5",
            'description'=>"тест",
            'location_channel'=>"1234",
            'company_id'=>$company->id,
            'is_active'=>true,
            'can_booking'=>true,
        ]);
    }
}
