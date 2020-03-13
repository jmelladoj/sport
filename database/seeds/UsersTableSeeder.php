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
            'password' => bcrypt('123456'),
            'tipo_usuario' => 1
        ]);
    }
}
