<?php

namespace App\Http\Controllers;

use App\Especialidad;
use Illuminate\Http\Request;

class EspecialidadController extends Controller
{
    public function index()
    {
        //
        return response()->json(Especialidad::with('servicios')->orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        //
        try {
            return Especialidad::create($request->all());
        } catch (\Exception $e) {
            if($e->getCode() == 23000){
                $especialidad =  Especialidad::onlyTrashed()->where('nombre', $request->nombre)->first();

                if($especialidad){
                    $especialidad->restore();
                }
            }
        }
    }

    public function show($id)
    {
        //
        return Especialidad::find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $especialidad = Especialidad::find($id);
        $especialidad->fill($request->all());
        $especialidad->save();
    }

    public function destroy($id)
    {
        //
        $especialidad = Especialidad::withTrashed()->whereId($id)->first();

        if($especialidad->deleted_at){
            $especialidad->servicios()->restore();
            $especialidad->restore();
        } else {
            $especialidad->servicios()->delete();
            $especialidad->delete();
        }

    }
}
