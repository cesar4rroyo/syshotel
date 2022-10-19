<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('business')->insert([
            'name' => 'Business 1',
            'address' => 'Address 1',
            'phone' => 'Phone 1',
            'email' => 'Email 1',
            'city' => 'Chiclayo',
        ]);
    }
}