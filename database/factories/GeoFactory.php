<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\Geo;
use App\Models\User;

class GeoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Geo::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'region' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'city' => $this->faker->city,
            'district' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'address' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'landmark' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'user_id' => User::factory(),
            'bot_id' => Bot::factory(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
