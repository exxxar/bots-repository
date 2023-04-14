<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Company;
use App\Models\Location;

class LocationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'images' => '{}',
            'lat' => $this->faker->latitude,
            'lon' => $this->faker->randomFloat(0, 0, 9999999999.),
            'address' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'description' => $this->faker->text,
            'location_channel' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'company_id' => Company::factory(),
            'is_active' => $this->faker->boolean,
            'can_booking' => $this->faker->boolean,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
