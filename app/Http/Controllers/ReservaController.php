<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\DetallePagos;
use App\DetalleReserva;
use App\HoraClinica;
use App\Profesional;
use App\Reserva;
use App\Servicio;
use App\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    //
    public function index($fecha_inicio, $fecha_termino){
        $reservas = DetalleReserva::whereBetween('fecha_servicio', [$fecha_inicio, $fecha_termino])->get();
        $horarios_mostrar = DetalleReserva::whereBetween('fecha_servicio', [$fecha_inicio, $fecha_termino])->get()->pluck('hora_clinicas_id');

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

        $reserva = Reserva::create([
            'pagada' => $request->reserva['pagada'],
            'profesional_id' => $request->reserva['profesional_id'],
            'cliente_id' => $cliente->id,
            'servicio_id' => $request->reserva['servicio_id']
        ]);

        $detalle = $request->detalle_reservas;
        
        foreach ($detalle as $key => $item) {
            DetalleReserva::create([
                'fecha_servicio' => $item['fecha_servicio'],
                'estado' => $item['estado'],
                'hora_clinicas_id' => $item['hora_clinicas_id'],
                'reserva_id' => $reserva->id
            ]);
        }   
        
        if($request->reserva['pagada'] == 1){
            $venta = Venta::create([
                'subtotal' => $request->venta['subtotal'],
                'descuento' => $request->venta['descuento'],
                'total' => $request->venta['total'],
                'tipo_documento' => $request->venta['tipo_documento'],
                'observacion' => $request->venta['observacion'],
                'cliente_id' => $cliente->id
            ]);

            $detalle_pagos = $request->pagos;

            foreach ($detalle_pagos as $key => $item) {
                DetallePagos::create([
                    'venta_id' => $venta->id,
                    'monto_pago' => $item['monto_pago'],
                    'medio_pago' => $item['medio_pago'],
                    'numero_documento' => $item['numero_documento'],
                ]);
            }

        }

    }

    public function show($id)
    {
        //
        return Reserva::withTrashed()->find($id);
    }

    public function pago(Request $request, $id){
        $reserva = Reserva::find($id);

        $venta = Venta::create([
            'subtotal' => $request->venta['subtotal'],
            'descuento' => $request->venta['descuento'],
            'total' => $request->venta['total'],
            'tipo_documento' => $request->venta['tipo_documento'],
            'observacion' => $request->venta['observacion'],
        ]);

        $detalle_pagos = $request->pagos;

        foreach ($detalle_pagos as $key => $item) {
            DetallePagos::create([
                'venta_id' => $venta->id,
                'monto_pago' => $item['monto_pago'],
                'medio_pago' => $item['medio_pago'],
                'numero_documento' => $item['numero_documento'],
            ]);
        }

        $reserva->pagada = 1;
        $reserva->venta_id = $venta->id;
        $reserva->save();
    }

    public function update(Request $request, $id) 
    {
        //
        $reserva = DetalleReserva::find($id);
        $reserva->fill($request->all());
        $reserva->save();
    }

}
