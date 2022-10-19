<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->truncateTables([
            'usertypes',
            'business',
            'users',
            'menu_groups',
            'menu_options',
            'access',
        ]);

        $this->call([
            UserTypeSeeder::class,
            BusinessSeeder::class,
            UserSeeder::class,
            MenuGroupSeeder::class,
            MenuOptionSeeder::class,
            AccessSeeder::class,
        ]);
    }

    protected function truncateTables(array $tablas)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        foreach ($tablas as $tabla) {
            DB::table($tabla)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}