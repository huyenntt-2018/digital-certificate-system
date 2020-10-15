<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'created_at' => Carbon\Carbon::now(), 'updated_at' => Carbon\Carbon::now()],
            ['name' => 'doctor', 'created_at' => Carbon\Carbon::now(), 'updated_at' => Carbon\Carbon::now()]
        ]);
    }
}
