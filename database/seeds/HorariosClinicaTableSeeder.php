<?php

use App\HoraClinica;
use Illuminate\Database\Seeder;

class HorariosClinicaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        HoraClinica::create(['hora' => '08:00']);
        HoraClinica::create(['hora' => '08:30']);
        HoraClinica::create(['hora' => '09:00']);
        HoraClinica::create(['hora' => '09:30']);
        HoraClinica::create(['hora' => '10:00']);
        HoraClinica::create(['hora' => '10:30']);
        HoraClinica::create(['hora' => '11:00']);
        HoraClinica::create(['hora' => '11:30']);
        HoraClinica::create(['hora' => '12:00']);
        HoraClinica::create(['hora' => '12:30']);
    }
}
