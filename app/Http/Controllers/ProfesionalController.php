<?php

namespace App\Http\Controllers;

use App\Profesional;
use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    //
    public function index($consulta){
        switch ($consulta) {
            case 1:
                return ['profesionales' => Profesional::orderBy('nombre')->get()];
                break;
            case 2:
                return ['profesionales' => Profesional::onlyTrashed()->orderBy('nombre')->get()];
                break;
        }
    }

    public function validar_unico($texto){
        return json_encode(Profesional::where('run', $texto)->get()->count() > 0 ? false : true);
    }

    public function crear_actualizar(Request $request){
        try {
            if($request->id == 0){
                return Profesional::create($request->all());
            } else {
                return Profesional::where('id', $request->id)->update($request->all());
            }
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                $profesional = Profesional::onlyTrashed()->where('correo', $request->correo)->first();
                $profesional->restore();

                $request['id'] = $profesional->id;

                return Profesional::where('id', $profesional->id)->update($request->all());
            }
        }

    }

    public function borrar(Request $request){
        if($request->accion == 1){
            Profesional::find($request->id)->delete();
        } else {
            Profesional::onlyTrashed()->where('id', $request->id)->restore();
        }
    }
}
