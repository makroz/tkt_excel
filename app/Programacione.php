<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Programacione extends Model
{
    //protected $fillable = ['codigo','nombre','tipo','modalidad','nombre_curso','area_tematica','docente','aula','nsesiones','fecha_desde','fecha_hasta','horario','estado'];
    protected $fillable = ['codigo','nombre','tipo','modalidad','nombre_curso','area_tematica','docente','aula','piso','nsesiones','fecha_desde','fecha_hasta','frecuencia','estado'];

    /*
    public function cat_curso(){
    	return $this->belongsTo(CatCurso::class,'cat_curso_id');
    }
    */
    /*public function tipo(){
    	return $this->belongsTo(Prog_tipo::class);
    }*/

    public function hasProg_tipo(array $tipos)
    {
    	foreach($tipos as $tipo){
    		if($this->tipo_id === $tipo){
    			return true;
    		}
    	}
    	return false;
    }
}
