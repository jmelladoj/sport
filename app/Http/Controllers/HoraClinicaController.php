<?php

namespace App\Http\Controllers;

use App\HoraClinica;
use App\HorarioProfesional;
use App\Profesional;
use Exception;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HoraClinicaController extends Controller
{
    //
    public function index(){
        return response()->json(HoraClinica::withTrashed()->orderBy('hora')->get());
    }

    public function store(Request $request){
        try{
            $horario = HoraClinica::create($request->all());
        } catch (\Exception $e) {
            if($e->getCode() == 23000){
                $horario =  HoraClinica::onlyTrashed()->where('hora', $request->hora)->first();

                if($horario){
                    $horario->restore();
                }
            }
        }

        $profesionales = Profesional::withTrashed()->get();

        for($i = 1; $i <= 6; $i++){
            foreach ($profesionales as $p) {
                HorarioProfesional::create(
                    [
                        'hora'             => $horario->hora,
                        'profesional_id'   => $p->id,
                        'hora_clinicas_id' => $horario->id,
                        'dia'              => $i,
                        'deleted_at'       => $p->deleted_at ? Carbon::now() : null
                    ]
                );
            }
        }

        return $horario;
    }

    public function show($id)
    {
        //
        return HoraClinica::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $hora = HoraClinica::find($id);
        $hora->fill($request->all());
        $hora->save();
    }

    public function destroy($id){
        $hora = HoraClinica::withTrashed()->whereId($id)->first();

        if($hora->deleted_at){
            $hora->restore();
        } else {
            $hora->delete();
        }
    }

    public function unico($texto){
        $hora = HoraClinica::where('hora', $texto)->first();
        return response()->json($hora ? false : true);
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

        return response()->json(
            [ 
                'fecha' => $fecha->format('Y-m-d'),
                'lunes' => $fecha->format('Y-m-d'),
                'martes' => $fecha->addDay('1')->format('Y-m-d'),
                'miercoles' => $fecha->addDay('1')->format('Y-m-d'),
                'jueves' => $fecha->addDay('1')->format('Y-m-d'),
                'viernes' => $fecha->addDay('1')->format('Y-m-d'),
                'sabado' => $fecha->addDay('1')->format('Y-m-d')
            ]
        );

    }
}
