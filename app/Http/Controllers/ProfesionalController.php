<?php

namespace App\Http\Controllers;

use App\HoraClinica;
use App\HorarioProfesional;
use App\Profesional;
use Illuminate\Http\Request;

class ProfesionalController extends Controller
{
    //
    public function index()
    {
        //
        return response()->json(Profesional::withTrashed()->orderBy('nombre')->get());
    }

    public function store(Request $request)
    {
        //
        try {
            $profesional =  Profesional::create($request->all());
        } catch (\Exception $e) {
            if($e->getCode() == 23000){
                $profesional =  Profesional::onlyTrashed()->where('run', $request->run)->first();

                if($profesional){
                    $profesional->restore();

                    $profesional->fill($request->all());
                    $profesional->save();
                }
            }
        }

        $horarios = HoraClinica::withTrashed()->get();

        foreach($horarios AS $h){
            for($i = 1; $i <= 6; $i++){
                HorarioProfesional::create(
                    [
                        'dia' => $i,
                        'hora' => $h->hora,
                        'profesional_id' => $profesional->id,
                        'hora_clinicas_id' => $h->id
                    ]
                );
            }

        }

        return $profesional;
    }

    public function show($id)
    {
        //
        return Profesional::withTrashed()->find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $profesional = Profesional::find($id);
        $profesional->fill($request->all());
        $profesional->save();
    }

    public function destroy($id)
    {
        //
        $profesional = Profesional::withTrashed()->whereId($id)->first();

        if($profesional->deleted_at){
            $profesional->restore();
        } else {
            $profesional->delete();
        }
    }

    public function unico($texto){
        $profesional = Profesional::where('run', $texto)->first();
        return response()->json($profesional ? false : true);
    }
}
