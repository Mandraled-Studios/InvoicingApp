<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Country;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Country::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sort_name' => $this->faker->regexify('[A-Za-z0-9]{5}'),
            'name' => $this->faker->name(),
            'phonecode' => $this->faker->regexify('[A-Za-z0-9]{6}'),
        ];
    }
}
