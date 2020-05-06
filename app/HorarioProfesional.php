<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HorarioProfesional extends Model
{
    //
    use SoftDeletes;

    protected $table = 'horario_profesionales';
    protected $guarded = ['id'];
    protected $appends = ['tipo_hora'];

    public function getTipoHoraAttribute(){
        return $this->horaClinica->nombreTipoHora;
    }

    public function horaClinica(){
        return $this->belongsTo(HoraClinica::class, 'hora_clinicas_id')->withTrashed();
    }
}
