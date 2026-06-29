<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendancesTableSeeder extends Seeder
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
            'user_id' => 1,
            'date_id' => 1,
            'start_time' => '2026-06-01 08:00:00',
            'end_time' => '2026-06-01 17:00:00',
            'category' => '出勤',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 1,
            'start_time' => '2026-06-01 12:00:00',
            'end_time' => '2026-06-01 13:00:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 1,
            'start_time' => '2026-06-01 09:00:00',
            'end_time' => '2026-06-01 09:15:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 1,
            'start_time' => '2026-06-01 10:00:00',
            'end_time' => '2026-06-01 10:15:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 2,
            'start_time' => '2026-06-02 08:00:00',
            'end_time' => '2026-06-02 17:00:00',
            'category' => '出勤',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 2,
            'start_time' => '2026-06-02 09:00:00',
            'end_time' => '2026-06-02 09:15:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 2,
            'start_time' => '2026-06-02 12:00:00',
            'end_time' => '2026-06-02 13:00:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 3,
            'start_time' => '2026-06-03 12:00:00',
            'end_time' => '2026-06-03 13:00:00',
            'category' => '休憩',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 3,
            'start_time' => '2026-06-03 08:00:00',
            'end_time' => '2026-06-03 21:15:00',
            'category' => '出勤',
            'status' => 1
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 3,
            'start_time' => '2026-06-03 11:00:00',
            'end_time' => '2026-06-03 13:00:00',
            'category' => '休憩',
            'status' => 2
        ];
        DB::table('attendances')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 3,
            'start_time' => '2026-06-03 08:00:00',
            'end_time' => '2026-06-03 21:45:00',
            'category' => '出勤',
            'status' => 2
        ];
        DB::table('attendances')->insert($param);
    }
}
