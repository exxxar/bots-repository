<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Role;
use App\Models\User;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'name' => $this->faker->name,
            'password' => $this->faker->password,
            //'fio_from_telegram' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'telegram_chat_id' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            //'age' => $this->faker->numberBetween(-10000, 10000),
            ///'birthday' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            //sex' => $this->faker->numberBetween(-10000, 10000),
            'avatar_url' => $this->faker->regexify('[A-Za-z0-9]{255}'),
           // 'city' => $this->faker->city,
            //'country' => $this->faker->country,
            //'address' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'role_id' => Role::factory(),
            'blocked_at' => $this->faker->dateTime(),
            'blocked_message' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
            'deleted_at' => $this->faker->dateTime(),
        ];
    }
}
