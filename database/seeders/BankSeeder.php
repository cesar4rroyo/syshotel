<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->insert([
            'description' => 'BBVA',
        ]);

        DB::table('banks')->insert([
            'description' => 'BCP',
        ]);

        DB::table('banks')->insert([
            'description' => 'Interbank',
        ]);

        DB::table('banks')->insert([
            'description' => 'Scotiabank',
        ]);

        DB::table('banks')->insert([
            'description' => 'Caja Arequipa',
        ]);

        DB::table('banks')->insert([
            'description' => 'BanBif',
        ]);

        DB::table('banks')->insert([
            'description' => 'Banco Pichincha',
        ]);
    }
}