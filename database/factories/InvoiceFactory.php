<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Client;
use App\Models\Invoice;

class InvoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Invoice::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_number' => $this->faker->regexify('[A-Za-z0-9]{64}'),
            'invoice_date' => $this->faker->dateTime(),
            'invoice_duedate' => $this->faker->dateTime(),
            'taxes_for_line_item' => $this->faker->boolean(),
            'invoice_subtotal' => $this->faker->randomFloat(2, 0, 999999.99),
            'tax1_label' => $this->faker->word(),
            'tax1_value' => $this->faker->randomFloat(2, 0, 999999.99),
            'tax2_label' => $this->faker->word(),
            'tax2_value' => $this->faker->randomFloat(2, 0, 999999.99),
            'round_off' => $this->faker->randomFloat(2, 0, 999999.99),
            'discount_value' => $this->faker->randomFloat(2, 0, 999999.99),
            'discount_type' => $this->faker->word(),
            'invoice_total' => $this->faker->randomFloat(2, 0, 999999.99),
            'paid_to_date' => $this->faker->randomFloat(2, 0, 999999.99),
            'balance_due' => $this->faker->randomFloat(2, 0, 999999.99),
            'client_id' => Client::factory(),
        ];
    }
}
