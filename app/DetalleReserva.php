<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetalleReserva extends Model
{
    //
    use SoftDeletes;
    
    protected $guarded = ['id'];
    protected $appends = ['clase_div', 'nombre_cliente', 'nombre_servicio', 'nombre_profesional', 'profesional_id'];

    public function getClaseDivAttribute(){
        switch ($this->estado) {
            case 1:
                return "warning";
                break;

            case 2:
                return "success";
                break;

            case 3:
                return "danger";
                break;
        }
    }

    public function getNombreClienteAttribute(){
        return $this->reserva->cliente ? $this->reserva->cliente->nombre : 'Desconocido';
    }

    public function getNombreServicioAttribute(){
        return $this->reserva->servicio ? $this->reserva->servicio->nombre : 'Desconocido';
    }

    public function getNombreProfesionalAttribute(){
        return $this->reserva->profesional ? $this->reserva->profesional->nombre : 'Desconocido';
    }

    public function getProfesionalIdAttribute(){
        return $this->reserva->profesional ? $this->reserva->profesional->id : '0';
    }

    public function reserva(){
        return $this->belongsTo(Reserva::class, 'reserva_id')->withTrashed();
    }
}
