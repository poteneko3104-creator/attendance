<?php

namespace Database\Factories;

use App\Models\Application;
use App\Models\Date;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Application::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = $this->faker->randomElement([1, 2]);

        return [
            'user_id' => User::factory(),
            'date_id' => Date::factory(),

            'application_date' => function (array $attributes) {
                $dateRecord = Date::find($attributes['date_id']) ?? Date::factory()->create();
                return Carbon::parse($dateRecord->date)->setTime(rand(17, 21), rand(0, 59), 0);
            },

            'approved_date' => function (array $attributes) use ($status) {
                if ($status === 2) {
                    return null;
                }

                $appDate = Carbon::parse($attributes['application_date']);
                return $appDate->copy()->addMinutes(rand(180, 1440));
            },
            'status' => $status,
        ];
    }
}
