<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert([
            'description' => 'Izipay',
        ]);

        DB::insert([
            'description' => 'Niubiz',
        ]);

        DB::insert([
            'description' => 'Culqi',
        ]);
    }
}