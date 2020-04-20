<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Especialidad extends Model
{
    //
    use SoftDeletes;

    protected $table = 'especialidades';
    protected $guarded = ['id'];

    public function servicios(){
        return $this->hasMany(Servicio::class, 'especialidad_id')->withTrashed();
    }
}
