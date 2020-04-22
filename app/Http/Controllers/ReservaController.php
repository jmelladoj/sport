<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\HoraClinica;
use App\Profesional;
use App\Reserva;
use App\Servicio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    //
    public function index($fecha_inicio, $fecha_termino){
        $reservas = Reserva::whereBetween('fecha_servicio', [$fecha_inicio, $fecha_termino])->get();
        $horarios_mostrar = Reserva::whereBetween('fecha_servicio', [$fecha_inicio, $fecha_termino])->get()->pluck('hora_clinicas_id');

        return [
                'reservas' => $reservas,
                'horas' => HoraClinica::whereIn('id', $horarios_mostrar)->orderBy('hora')->get()
            ];
    }

    public function store(Request $request)
    {
        $cliente = Cliente::withTrashed()->where('run', $request->cliente['run'])->first();
        
        if($cliente){
            $cliente->restore();
            $cliente->update($request->cliente);
        } else {
            $cliente = Cliente::create($request->cliente);
        }

        $reservas = $request->reservas;

        foreach ($reservas as $key => $item) {
            $item['cliente_id'] = $cliente->id;
            $item['nombre_cliente'] = $cliente->nombre;
            Reserva::create($item);
        }        
        //

    }

    public function show($id)
    {
        //
        return Reserva::withTrashed()->find($id);
    }

    public function update(Request $request, $id)
    {
        //
        $reserva = Reserva::find($id);
        $reserva->fill($request->all());
        $reserva->save();
    }

}
