<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Date;
use App\Models\Attendance;
use App\Models\Application;

class DatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $param = [
            'id' => 1,
            'user_id' => 1,
            'date' => '2026-06-01',
            'application' => 1,
            'status' => 3,
        ];
        DB::table('dates')->insert($param);
        $param = [
            'id' => 2,
            'user_id' => 1,
            'date' => '2026-06-02',
            'application' => 1,
            'status' => 3,
        ];
        DB::table('dates')->insert($param);

        Date::factory()->count(20)->create()->each(function ($date) {

            if ($date->application === 2) {

                Attendance::factory()->create([
                    'user_id' => $date->user_id,
                    'date_id' => $date->id,
                    'category' => '出勤',
                    'status' => 1,
                ]);

                Attendance::factory()->create([
                    'user_id' => $date->user_id,
                    'date_id' => $date->id,
                    'category' => '出勤',
                    'status' => 2,
                ]);

                Application::factory()->create([
                    'user_id' => $date->user_id,
                    'date_id' => $date->id,
                    'status' => 2,
                ]);

            } else {
                Attendance::factory()->create([
                    'user_id' => $date->user_id,
                    'date_id' => $date->id,
                    'category' => '出勤',
                    'status' => 1,
                ]);
            }

            if (rand(1, 100) <= 50) {
                Attendance::factory()->create([
                    'user_id' => $date->user_id,
                    'date_id' => $date->id,
                    'category' => '休憩',
                    'status' => 1,
                ]);
            }
        });

    }
}
