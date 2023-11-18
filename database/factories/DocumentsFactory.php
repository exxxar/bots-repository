<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Bot;
use App\Models\BotUser;
use App\Models\Documents;

class DocumentsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Documents::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->text,
            'file_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'type' => $this->faker->numberBetween(-10000, 10000),
            'params' => '{}',
            'bot_id' => Bot::factory(),
            'bot_user_id' => BotUser::factory(),
            'verified_at' => $this->faker->dateTime(),
        ];
    }
}
