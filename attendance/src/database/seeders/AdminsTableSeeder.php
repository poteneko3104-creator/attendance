<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminsTableSeeder extends Seeder
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
            'name' => 'admin',
            'email' => 'admin@aaa.com',
            'password' => Hash::make('admin1234'),
            'remember_token' => Str::random(10),
        ];
        DB::table('admins')->insert($param);
    }
}
