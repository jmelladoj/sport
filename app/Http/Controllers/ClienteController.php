<?php

namespace App\Http\Controllers;

use App\Cliente;
use Exception;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    //
    public function index()
    {
        //
        return response()->json(Cliente::withTrashed()->orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        //
        try {
            return Cliente::create($request->all());
        } catch (\Exception $e) {
            if($e->getCode() == 23000){
                $cliente =  Cliente::onlyTrashed()->where('run', $request->run)->first();

                if($cliente){
                    $cliente->restore();

                    $cliente->fill($request->all());
                    $cliente->save();
                }
            }
        }
    }

    public function show($id)
    {
        //
        return Cliente::withTrashed()->find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $cliente = Cliente::find($id);
        $cliente->fill($request->all());
        $cliente->save();
    }

    public function destroy($id)
    {
        //
        $cliente = Cliente::withTrashed()->whereId($id)->first();

        if($cliente->deleted_at){
            $cliente->restore();
        } else {
            $cliente->delete();
        }
    }

    public function unico($texto){
        return response()->json(Cliente::where('run', $texto)->get()->count() > 0 ? false : true);
    }
}
