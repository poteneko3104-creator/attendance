<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

    }
}
