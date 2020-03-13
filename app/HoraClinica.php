<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HoraClinica extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['nombre_tipo_hora'];

    public function getNombreTipoHoraAttribute(){
        switch ($this->tipo_hora) {
            case 1:
                return 'Hora normal';
                break;
            case 2:
                return 'Hora de sobrecupo';
                break;
        }
    }
}
