<?php

namespace App\Http\Controllers;

use App\Especialidad;
use Exception;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    //
    public function index($consulta){
        switch ($consulta) {
            case 1:
                return ['especialidades' => Especialidad::orderBy('nombre')->get()];
                break;
            case 2:
                return ['especialidades' => Especialidad::onlyTrashed()->orderBy('nombre')->get()];
                break;
        }
    }

    public function validar_unico($texto){
        return json_encode(Especialidad::where('nombre', $texto)->get()->count() > 0 ? false : true);
    }

    public function crear_actualizar(Request $request){
        try {
            if($request->id == 0){
                return Especialidad::create($request->all());
            } else {
                return Especialidad::where('id', $request->id)->update($request->all());
            }
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                $especialidad =  Especialidad::onlyTrashed()->where('nombre', $request->nombre)->first();
                $especialidad->restore();

                $request['id'] = $especialidad->id;

                return Especialidad::where('id', $especialidad->id)->update($request->all());
            }
        }

    }

    public function borrar(Request $request){
        if($request->accion == 1){
            Especialidad::find($request->id)->delete();
        } else {
            Especialidad::onlyTrashed()->where('id', $request->id)->restore();
        }
    }
}
