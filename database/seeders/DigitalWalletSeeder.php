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
        DB::table('digitalwallets')->insert([
            'description' => 'Yape',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'Plin',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'Tunki',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'Lukita',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'BIM',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'Agora PAY',
        ]);

        DB::table('digitalwallets')->insert([
            'description' => 'BBVA Wallet',
        ]);
    }
}