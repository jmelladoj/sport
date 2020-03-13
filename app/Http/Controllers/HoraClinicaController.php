<?php

namespace App\Http\Controllers;

use App\HoraClinica;
use Exception;
use Illuminate\Http\Request;

class HoraClinicaController extends Controller
{
    //
    public function index($consulta){
        switch ($consulta) {
            case 1:
                return ['horas' => HoraClinica::orderBy('hora')->get()];
                break;
            case 2:
                return ['horas' => HoraClinica::onlyTrashed()->orderBy('hora')->get()];
                break;
        }
    }

    public function validar_unico($texto){
        return json_encode(HoraClinica::where('hora', $texto)->get()->count() > 0 ? false : true);
    }

    public function crear_actualizar(Request $request){
        try {
            if($request->id == 0){
                return HoraClinica::create($request->all());
            } else {
                return HoraClinica::where('id', $request->id)->update($request->all());
            }
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                $hora =  HoraClinica::onlyTrashed()->where('hora', $request->hora)->first();
                $hora->restore();

                $request['id'] = $hora->id;

                return HoraClinica::where('id', $hora->id)->update($request->all());
            }
        }

    }

    public function borrar(Request $request){
        if($request->accion == 1){
            HoraClinica::find($request->id)->delete();
        } else {
            HoraClinica::onlyTrashed()->where('id', $request->id)->restore();
        }
    }
}
