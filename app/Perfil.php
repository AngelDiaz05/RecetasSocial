<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    //Relacion 1:1 de Perfil con usuario
    public function usuario(){
        return $this->belongsTo(User::class,'user_id'); //-> hay que indicar que columna de la tabla esta relacionada
    }
}
