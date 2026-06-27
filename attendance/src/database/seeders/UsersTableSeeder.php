<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
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
            'name' => 'user1',
            'email' => 'user@aaa.com',
            'password' => Hash::make('user1234'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
        DB::table('users')->insert($param);
    }
}
