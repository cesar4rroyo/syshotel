<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FloorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('floors')->insert([
            'name' => 'Floor 1',
            'branch_id' => 1,
            'business_id' => 1,
        ]);

        DB::table('floors')->insert([
            'name' => 'Floor 2',
            'branch_id' => 1,
            'business_id' => 1,
        ]);
    }
}
