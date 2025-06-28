<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Receipt;

class ReceiptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Receipt::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'receipt_number' => $this->faker->regexify('[A-Za-z0-9]{64}'),
            'payment_amount' => $this->faker->randomFloat(2, 0, 999999.99),
            'assignment' => '{}',
            'assigned_amount' => $this->faker->randomFloat(2, 0, 999999.99),
            'receipt_date' => $this->faker->dateTime(),
        ];
    }
}
