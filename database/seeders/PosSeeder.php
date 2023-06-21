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
        DB::table('pos')->insert([
            'description' => 'Izipay',
        ]);

        DB::table('pos')->insert([
            'description' => 'Niubiz',
        ]);

        DB::table('pos')->insert([
            'description' => 'Culqi',
        ]);
    }
}