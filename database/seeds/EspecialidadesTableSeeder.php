<?php

use App\Especialidad;
use Illuminate\Database\Seeder;

class EspecialidadesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Especialidad::create(['nombre' => 'KINESIOLOGIA']);
        Especialidad::create(['nombre' => 'NUTRICION']);
    }
}
