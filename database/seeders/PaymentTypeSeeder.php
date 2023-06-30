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
        DB::table('paymenttypes')->insert([
            'description' => 'Efectivo',
        ]);

        DB::table('paymenttypes')->insert([
            'description' => 'Tarjeta',
        ]);

        DB::table('paymenttypes')->insert([
            'description' => 'Billetera Digital',
        ]);

        DB::table('paymenttypes')->insert([
            'description' => 'Transferencia',
        ]);

        DB::table('paymenttypes')->insert([
            'description' => 'Depósito',
        ]);
    }
}