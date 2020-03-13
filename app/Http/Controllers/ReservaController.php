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

    public function index_horas_profesional($id, $fecha) {
        $horas_ocupadas = Reserva::where('profesional_id', $id)->where('fecha_servicio', $fecha)->get()->pluck('hora_clinicas_id');

        return ['horas' => HoraClinica::whereNotIn('id', $horas_ocupadas)->orderBy('hora')->get()];
    }

    public function agendar(Request $request){
        DB::transaction(function () use ($request) {

            $profesional = Profesional::find($request->profesional_id);
            $servicio = Servicio::find($request->servicio_id);

            try {
                if($request->cliente['id'] == 0){
                    $cliente = Cliente::create($request->cliente);
                } else {
                    $cliente = Cliente::where('id', $request->cliente['id'])->update($request->cliente);
                    $cliente = Cliente::find($request->cliente['id']);
                }
            } catch (Exception $e) {
                if($e->getCode() == 23000){
                    $cliente = Cliente::onlyTrashed()->where('run', $request->cliente['run'])->first();
                    $cliente->restore();

                    $request['id'] = $request->cliente['id'];

                    $cliente = Cliente::where('id', $cliente['id'])->update($request->cliente);
                }
            }


            Reserva::create([
                'nombre_cliente' => $cliente->nombre,
                'nombre_servicio' => $servicio->nombre,
                'nombre_profesional' => $profesional->nombre,
                'fecha_servicio' => $request->fecha_servicio,
                'cliente_id' => $cliente->id,
                'servicio_id' => $servicio->id,
                'profesional_id' => $profesional->id,
                'hora_clinicas_id' => $request->hora_id
            ]);
        });
    }

    public function actualizar_estado_reserva(Request $request){
        $reserva = Reserva::find($request->id);
        $reserva->estado = $request->estado;
        $reserva->observacion = $request->observacion;
        $reserva->save();
    }
}
