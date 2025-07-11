<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\TaskType;

class TaskTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TaskType::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'task_name' => $this->faker->word(),
            'hourly_rate' => $this->faker->randomFloat(2, 0, 9999.99),
            'belongsTo' => $this->faker->word(),
        ];
    }
}
