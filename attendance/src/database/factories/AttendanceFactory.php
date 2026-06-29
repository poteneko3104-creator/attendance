<?php

namespace Database\Factories;
use App\Models\Attendance;
use App\Models\Date;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Attendance::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('-1 month', 'now');
        $category = $this->faker->randomElement(['出勤', '休憩']);
        $endTime = null;
        if ($this->faker->boolean(80)) {
            $clonedStart = clone $startTime;
            if ($category === '出勤') {
                $endTime = $this->faker->dateTimeBetween($clonedStart, $clonedStart->modify('+' . rand(4, 9) . ' hours'));
            } else {
                $endTime = $this->faker->dateTimeBetween($clonedStart, $clonedStart->modify('+' . rand(15, 60) . ' minutes'));
            }
        }
        return [
            'user_id' => User::factory(),
            'date_id' => Date::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'category' => $category,
            'status' => $this->faker->randomElement([0, 1, 2]),
        ];
        $startTime = $this->faker->dateTimeBetween('-1 month', 'now');
        $category = $this->faker->randomElement(['出勤', '休憩']);
        $endTime = null;

        if ($this->faker->boolean(80)) {
            $clonedStart = clone $startTime;
            if ($category === '出勤') {
                $endTime = $this->faker->dateTimeBetween($clonedStart, $clonedStart->modify('+' . rand(4, 9) . ' hours'));
            } else {
                $endTime = $this->faker->dateTimeBetween($clonedStart, $clonedStart->modify('+' . rand(15, 60) . ' minutes'));
            }
        }
        return [
            'user_id' => User::factory(),
            'date_id' => Date::factory(),
            'start_time' => $startTime,
            'end_time' => $endTime,
            'category' => $category,
            'status' => $this->faker->randomElement([0, 1, 2]),
        ];
    }
}
