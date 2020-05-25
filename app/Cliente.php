<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];

    public function reservas(){
        return $this->hasMany(Reserva::class, 'cliente_id')->with('detalle_reservas')->withTrashed();
    }
}
