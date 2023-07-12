<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\ProductOption;

class ProductOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductOption::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'title' => $this->faker->sentence(4),
            'value' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'section' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'product_id' => Product::factory(),
        ];
    }
}
