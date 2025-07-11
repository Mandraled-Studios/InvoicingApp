<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Collection;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'default_quantity' => $this->faker->numberBetween(-10000, 10000),
            'price' => $this->faker->randomFloat(2, 0, 999999.99),
            'hsncode' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean(),
            'collection_id' => Collection::factory(),
        ];
    }
}
