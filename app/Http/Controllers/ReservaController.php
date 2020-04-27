<?php

namespace App\Http\Controllers;

use App\Cliente;
use App\DetallePagos;
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

        $venta = Venta::create([
            'subtotal' => $request->venta['subtotal'],
            'descuento' => $request->venta['descuento'],
            'total' => $request->venta['total'],
            'tipo_documento' => $request->venta['tipo_documento'],
            'observacion' => $request->venta['observacion'],
            'nombre_cliente' => $cliente->nombre,
            'cliente_id' => $cliente->id
        ]);

        $detalle_pagos = $request->venta['pagos'];

        foreach ($detalle_pagos as $key => $item) {
            $item['venta_id'] = $venta->id;
            
            DetallePagos::create([
                'venta_id' => $venta->id,
                'monto_pago' => $item['monto_pago'],
                'medio_pago' => $item['medio_pago'],
                'numero_documento' => $item['numero_documento'],
            ]);
        }

        $reservas = $request->reservas;

        foreach ($reservas as $key => $item) {
            $item['cliente_id'] = $cliente->id;
            $item['nombre_cliente'] = $cliente->nombre;
            $item['venta_id'] = $venta->id;
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
