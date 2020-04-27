<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venta extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];

    public function pagos(){
        return $this->hasMany(DetallePagos::class, 'venta_id');
    }
}
