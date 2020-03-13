<?php

namespace App\Http\Controllers;

use App\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    //
    public function index($consulta){
        switch ($consulta) {
            case 1:
                return ['servicios' => Servicio::orderBy('nombre')->get()];
                break;
            case 2:
                return ['servicios' => Servicio::onlyTrashed()->orderBy('nombre')->get()];
                break;
        }
    }

    public function crear_actualizar(Request $request){
        try {
            if($request->id == 0){
                return Servicio::create($request->all());
            } else {
                return Servicio::where('id', $request->id)->update($request->all());
            }
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                $servicio = Servicio::onlyTrashed()->where('correo', $request->correo)->first();
                $servicio->restore();

                $request['id'] = $servicio->id;

                return Servicio::where('id', $servicio->id)->update($request->all());
            }
        }

    }

    public function borrar(Request $request){
        if($request->accion == 1){
            Servicio::find($request->id)->delete();
        } else {
            Servicio::onlyTrashed()->where('id', $request->id)->restore();
        }
    }
}
