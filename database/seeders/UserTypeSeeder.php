<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usertypes')->insert([
            'name' => 'Administrador Principal Hotel Hub',
        ]);

        DB::table('usertypes')->insert([
            'name' => 'Administrador Empresa',
        ]);

        DB::table('usertypes')->insert([
            'name' => 'Administrador Sucursal',
        ]);

        DB::table('usertypes')->insert([
            'name' => 'Cajero',
        ]);
    }
}
