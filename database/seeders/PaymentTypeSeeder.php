<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert([
            'description' => 'Efectivo',
        ]);

        DB::insert([
            'description' => 'Tarjeta',
        ]);

        DB::insert([
            'description' => 'Billetera Digital',
        ]);

        DB::insert([
            'description' => 'Transferencia',
        ]);

        DB::insert([
            'description' => 'Depósito',
        ]);
    }
}