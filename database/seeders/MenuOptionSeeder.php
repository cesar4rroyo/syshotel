<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menu_options')->insert([
            'name' => 'Usuario',
            'link' => 'user',
            'icon' => 'fas fa-user',
            'order' => 1,
            'menugroup_id' => 3
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Roles',
            'link' => 'role',
            'icon' => 'fas fa-users-cog',
            'order' => 2,
            'menugroup_id' => 3
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Tipos Usuario',
            'icon' => 'fas fa-users-slash',
            'link' => 'usertype',
            'order' => 4,
            'menugroup_id' => 3
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Accesos',
            'link' => 'access',
            'icon' => 'fas fa-people-arrows',
            'order' => 5,
            'menugroup_id' => 3
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Opciones de Menú',
            'icon' => 'fas fa-stream',
            'link' => 'menuoption',
            'order' => 6,
            'menugroup_id' => 3
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Grupos de Menú',
            'icon' => 'fas fa-list-ol',
            'link' => 'menugroup',
            'order' => 7,
            'menugroup_id' => 3
        ]);
        //end Grupo Usuarios

        DB::table('menu_options')->insert([
            'name' => 'Lista de Comprobantes',
            'icon' => 'fas fa-list-ol',
            'link' => 'bussiness',
            'order' => 7,
            'menugroup_id' => 5
        ]);
        DB::table('menu_options')->insert([
            'name' => 'Nota de Crédito',
            'icon' => 'fas fa-list-ol',
            'link' => 'bussiness',
            'order' => 7,
            'menugroup_id' => 5
        ]);

        DB::table('menu_options')->insert([
            'name' => 'Empresas',
            'icon' => 'fas fa-list-ol',
            'link' => 'bussiness',
            'order' => 7,
            'menugroup_id' => 6
        ]);
    }
}