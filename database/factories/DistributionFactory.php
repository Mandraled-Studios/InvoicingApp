<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Distribution;

class DistributionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Distribution::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => $this->faker->randomNumber(),
            'receipt_id' => $this->faker->randomNumber(),
            'amount_assigned' => $this->faker->randomFloat(2, 0, 999999.99),
            'balance_amount' => $this->faker->randomFloat(2, 0, 999999.99),
            'applied_date' => $this->faker->dateTime(),
        ];
    }
}
