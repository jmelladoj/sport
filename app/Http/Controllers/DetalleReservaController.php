<?php

namespace App\Http\Controllers;

use App\DetalleReserva;
use Illuminate\Http\Request;

class DetalleReservaController extends Controller
{
    //
    public function show($id){
        //
        return DetalleReserva::find($id);
    }

    public function update(Request $request, $id) 
    {
        //
        $detalle = DetalleReserva::find($id);
        $detalle->fill($request->all());
        $detalle->save();
    }
}
