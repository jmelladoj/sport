<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    //
    use SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['clase_div'];

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
}
