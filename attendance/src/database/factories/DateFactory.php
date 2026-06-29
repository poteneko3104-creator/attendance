<?php

namespace Database\Factories;

use App\Models\Date;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Date::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('-3 month', 'now')->format('Y-m-d'),
            'application' => $this->faker->randomElement([1, 2]),
            'remarks' => $this->faker->optional(0.3)->realText(20),
            'status' => $this->faker->randomElement([1, 2, 3]),
        ];
    }
}
