<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('branches')->insert([
            'name' => 'Branch 1',
            'address' => 'Address 1',
            'phone' => 'Phone 1',
            'email' => 'Email 1',
            'city' => 'Chiclayo',
            'business_id' => 1,
            'status' => 'A',
        ]);
    }
}