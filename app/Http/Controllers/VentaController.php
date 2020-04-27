<?php

namespace App\Http\Controllers;

use App\DetallePagos;
use App\Venta;
use Illuminate\Http\Request;

class VentaController extends Controller
{
    //
    public function index(){
        $ventas = Venta::with('pagos')->orderBy('id', 'desc')->get();
        $detalle = DetallePagos::get();

        return response()->json([
            'ventas' => $ventas,
            'detalle' => $detalle
        ]);
    }
}
