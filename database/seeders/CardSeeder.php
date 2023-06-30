<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cards')->insert([
            'description' => 'Visa',
            'type' => 'CREDITO'
        ]);

        DB::table('cards')->insert([
            'description' => 'MasterCard',
            'type' => 'CREDITO'
        ]);

        DB::table('cards')->insert([
            'description' => 'American Express',
            'type' => 'CREDITO'
        ]);

        DB::table('cards')->insert([
            'description' => 'Diners Club',
            'type' => 'CREDITO'
        ]);

        DB::table('cards')->insert([
            'description' => 'Visa',
            'type' => 'DEBITO'
        ]);

        DB::table('cards')->insert([
            'description' => 'MasterCard',
            'type' => 'DEBITO'
        ]);
    }
}