<?php

namespace App\Http\Controllers;

use App\Cliente;
use Exception;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //
    public function index($consulta){
        switch ($consulta) {
            case 1:
                return ['clientes' => Cliente::orderBy('nombre')->get()];
                break;
            case 2:
                return ['clientes' => Cliente::onlyTrashed()->orderBy('nombre')->get()];
                break;
        }
    }

    public function validar_unico($texto){
        return json_encode(Cliente::where('run', $texto)->get()->count() > 0 ? false : true);
    }

    public function crear_actualizar(Request $request){
        try {
            if($request->id == 0){
                return Cliente::create($request->all());
            } else {
                return Cliente::where('id', $request->id)->update($request->all());
            }
        } catch (Exception $e) {
            if($e->getCode() == 23000){
                $cliente = Cliente::onlyTrashed()->where('correo', $request->correo)->first();
                $cliente->restore();

                $request['id'] = $cliente->id;

                return Cliente::where('id', $cliente->id)->update($request->all());
            }
        }

    }

    public function borrar(Request $request){
        if($request->accion == 1){
            Cliente::find($request->id)->delete();
        } else {
            Cliente::onlyTrashed()->where('id', $request->id)->restore();
        }
    }
}
