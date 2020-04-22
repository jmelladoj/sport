<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profesional extends Model
{
    //
    use SoftDeletes;

    protected $table = 'profesionales';
    protected $guarded = ['id'];
    protected $appends = ['nombre_especialidad'];

    public function especialidad(){
        return $this->belongsTo(Especialidad::class, 'especialidad_id')->withTrashed();
    }

    public function getNombreEspecialidadAttribute(){
        return $this->especialidad ? $this->especialidad->nombre : 'Sin especialidad';
    }

    public function horarios(){
        return $this->hasMany(HorarioProfesional::class, 'profesional_id')->withTrashed();
    }
}
