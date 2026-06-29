<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ApplicationsTableSeeder extends Seeder
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
            'application_date' => '2026-06-02 08:00:00',
            'approved_date' => '2026-06-02 17:00:00',
            'status' => 1
        ];
        DB::table('applications')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 1,
            'application_date' => '2026-06-08 08:00:00',
            'approved_date' => '2026-06-08 17:00:00',
            'status' => 1
        ];
        DB::table('applications')->insert($param);
        $param = [
            'user_id' => 1,
            'date_id' => 3,
            'application_date' => '2026-06-05 08:00:00',
            'status' => 2
        ];
        DB::table('applications')->insert($param);
    }
}
