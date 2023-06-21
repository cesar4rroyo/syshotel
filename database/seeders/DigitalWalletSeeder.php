<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DigitalWalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert([
            'description' => 'Yape',
        ]);

        DB::insert([
            'description' => 'Plin',
        ]);

        DB::insert([
            'description' => 'Tunki',
        ]);

        DB::insert([
            'description' => 'Lukita',
        ]);

        DB::insert([
            'description' => 'BIM',
        ]);

        DB::insert([
            'description' => 'Agora PAY',
        ]);

        DB::insert([
            'description' => 'BBVA Wallet',
        ]);
    }
}