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
}
