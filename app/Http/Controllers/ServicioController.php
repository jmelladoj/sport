<?php

namespace App\Http\Controllers;

use App\Especialidad;
use App\Servicio;
use Illuminate\Http\Request;

class ServicioController extends Controller
{
    public function index($id){
        return response()->json(Especialidad::whereId($id)->with('servicios')->orderBy('nombre')->first());
    }

    public function index_servicios(){
        return response()->json(Servicio::orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        //
        return Servicio::create($request->all());
    }

    public function show($id)
    {
        //
        return Servicio::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $servicio = Servicio::find($id);
        $servicio->fill($request->all());
        $servicio->save();
    }

    public function destroy($id)
    {
        //
        $servicio = Servicio::withTrashed()->whereId($id)->first();

        if($servicio->deleted_at){
            $servicio->restore();
        } else {
            $servicio->delete();
        }
    }
}
