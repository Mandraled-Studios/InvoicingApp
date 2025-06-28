<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\State;

class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'building_number' => $this->faker->regexify('[A-Za-z0-9]{64}'),
            'street_address' => $this->faker->word(),
            'location' => $this->faker->word(),
            'city_id' => City::factory(),
            'state_id' => State::factory(),
            'country_id' => Country::factory(),
            'zipcode' => $this->faker->regexify('[A-Za-z0-9]{20}'),
            'client_id' => Client::factory(),
        ];
    }
}
