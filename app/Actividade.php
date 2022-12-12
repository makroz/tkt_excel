<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use App\Evento;

class Actividade extends Model
{
    protected $fillable = ['eventos_id','titulo',
    'subtitulo', 
    'desc_actividad', 
    'desc_ponentes', 
    'vacantes', 
    'inscritos', 
    'fecha_desde', 
    'fecha_hasta', 
    'hora_inicio', 
    'hora_final', 
    'ubicacion', 
    'imagen', 
    'estado'];

    /*public function evento(){
    	return $this->belongsTo(Evento::class,'eventos_id');
    }*/
}

