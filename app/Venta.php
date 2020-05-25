<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Venta extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['fecha'];

    public function pagos(){
        return $this->hasMany(DetallePagos::class, 'venta_id');
    }

    public function getFechaAttribute(){
        return Carbon::parse($this->created_at)->format('d-m-Y H:i:s');
    }
}
