<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConceptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('concepts')->insert([
            'name' => 'Concept 1',
            'type' => 'Type 1',
            'branch_id' => 1,
            'business_id' => 1,
        ]);

        DB::table('concepts')->insert([
            'name' => 'Concept 2',
            'type' => 'Type 2',
            'branch_id' => 1,
            'business_id' => 1,
        ]);
    }
}