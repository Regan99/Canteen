<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
        	['role_name' => 'SuperAdmin','status' => 'active',],
            ['role_name' => 'School','status' => 'active',],
            ['role_name' => 'Kitchen','status' => 'active',],
            ['role_name' => 'Library','status' => 'active',],
        ]);
    }
}
