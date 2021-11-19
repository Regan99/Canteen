<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'uuid' => Str::uuid()->toString().mt_rand(5, 100000),
        	'role_id' => '2',
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'address' => 'baneshwor',
            'contact_number' => '1234567890',
            'status' => 'active',

        ]);
    }
}
