<?php

namespace App\Http\Controllers;

use App\DetalleReserva;
use App\HorarioProfesional;
use App\Reserva;
use Illuminate\Http\Request;

class HorarioProfesionalController extends Controller
{
    public function index_disponibilidad(Request $request)
    {
        //
        $dia = date('w', strtotime($request->fecha));

        $reservas = Reserva::where('profesional_id', $request->profesional_id)->get()->pluck('id');
        $detalle_reservas = DetalleReserva::where('fecha_servicio', $request->fecha)->whereIn('reserva_id', $reservas)->pluck('hora_clinicas_id');
        $horarios_profesional =  HorarioProfesional::where('profesional_id', $request->profesional_id)->where('dia', $dia)->where('trabaja', 1)->whereNotIn('hora_clinicas_id', $detalle_reservas)->get();

        return $horarios_profesional;
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        
    }

    public function show($id)
    {
        //
        return HorarioProfesional::where('profesional_id', $id)->where('trabaja', 1)->orderBy('dia')->orderBy('hora_clinicas_id')->get();
    }

    public function update(Request $request, $id)
    {
        //
        HorarioProfesional::where('profesional_id', $id)->update(['trabaja' => 0]);

        HorarioProfesional::where('profesional_id', $id)->where('dia', 1)->whereIn('hora_clinicas_id', array_values($request->lunes))->update(['trabaja' => 1]);
        HorarioProfesional::where('profesional_id', $id)->where('dia', 2)->whereIn('hora_clinicas_id', array_values($request->martes))->update(['trabaja' => 1]);
        HorarioProfesional::where('profesional_id', $id)->where('dia', 3)->whereIn('hora_clinicas_id', array_values($request->miercoles))->update(['trabaja' => 1]);
        HorarioProfesional::where('profesional_id', $id)->where('dia', 4)->whereIn('hora_clinicas_id', array_values($request->jueves))->update(['trabaja' => 1]);
        HorarioProfesional::where('profesional_id', $id)->where('dia', 5)->whereIn('hora_clinicas_id', array_values($request->viernes))->update(['trabaja' => 1]);
        HorarioProfesional::where('profesional_id', $id)->where('dia', 6)->whereIn('hora_clinicas_id', array_values($request->sabado))->update(['trabaja' => 1]);
    }

    public function destroy($id)
    {
        //
    }
}
