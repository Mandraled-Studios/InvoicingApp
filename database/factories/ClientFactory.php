<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\Client;

class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->word(),
            'logo' => $this->faker->word(),
            'website' => $this->faker->word(),
            'gstin' => $this->faker->regexify('[A-Za-z0-9]{64}'),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'firm_type' => $this->faker->regexify('[A-Za-z0-9]{128}'),
            'address_id' => Address::factory(),
        ];
    }
}
