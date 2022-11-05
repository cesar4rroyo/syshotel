<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rooms')->insert([
            'name' => 'Room 1',
            'number' => 101,
            'status' => 'D',
            'room_type_id' => 1,
            'floor_id' => 1,
            'branch_id' => 1,
            'business_id' => 1,
        ]);
        DB::table('rooms')->insert([
            'name' => 'Room 2',
            'number' => 102,
            'status' => 'D',
            'room_type_id' => 2,
            'floor_id' => 1,
            'branch_id' => 1,
            'business_id' => 1,
        ]);
    }
}