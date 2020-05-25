<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        User::create([
            'nombre' => 'Admin',
            'usuario' => 'admin',
            'password' => bcrypt('srcenter.2020*'),
            'tipo_usuario' => 1
        ]);

        User::create(['nombre' => 'Gustavo', 'usuario' => 'gustavo', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Belen', 'usuario' => 'belen', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Matias', 'usuario' => 'matias', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Jaime', 'usuario' => 'jaime', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Francisco', 'usuario' => 'francisco', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Cristian', 'usuario' => 'cristian', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Verena', 'usuario' => 'verena', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
        User::create(['nombre' => 'Camila', 'usuario' => 'camila', 'password' => bcrypt('temporal'), 'tipo_usuario' => 2]);
    }
}
