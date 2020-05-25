<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['clase_div', 'nombre_servicio', 'valor_servicio'];

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

    public function getNombreServicioAttribute(){
        return $this->servicio ? $this->servicio->nombre : 'Desconocido';
    }

    public function getValorServicioAttribute(){
        return $this->servicio ? $this->servicio->valor : '0';
    }

    public function cliente(){
        return $this->belongsTo(Cliente::class, 'cliente_id')->withTrashed();
    }

    public function servicio(){
        return $this->belongsTo(Servicio::class, 'servicio_id')->withTrashed();
    }

    public function profesional(){
        return $this->belongsTo(Profesional::class, 'profesional_id')->withTrashed();
    }

    public function detalle_reservas(){
        return $this->hasMany(DetalleReserva::class, 'reserva_id')->withTrashed();
    }
}
