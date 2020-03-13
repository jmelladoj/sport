<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function dias($accion, $fecha){
        if($fecha == 'sf'){
            $fecha = Carbon::now()->startOfWeek();
        }

        if($accion != 0){
            switch ($accion) {
                case '1':
                    $fecha = Carbon::parse($fecha)->addWeek('1')->startOfWeek();
                    break;
                case '2':
                    $fecha = Carbon::parse($fecha)->subWeek('1')->startOfWeek();
                    break;
            }
        }

        return [ 'fecha' => $fecha->format('Y-m-d'),
                 'lunes' => $fecha->format('Y-m-d'),
                 'martes' => $fecha->addDay('1')->format('Y-m-d'),
                 'miercoles' => $fecha->addDay('1')->format('Y-m-d'),
                 'jueves' => $fecha->addDay('1')->format('Y-m-d'),
                 'viernes' => $fecha->addDay('1')->format('Y-m-d'),
                 'sabado' => $fecha->addDay('1')->format('Y-m-d')
               ];

    }
}
