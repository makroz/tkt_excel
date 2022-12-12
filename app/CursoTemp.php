<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CursoTemp extends Model
{
    protected $table = 'cursos_temp';
    protected $fillable = ['id,nom_curso,descripcion,modalidad_id,tipo_id,cat_curso_id,sede_id,sesiones,horas_aca,repetido,mensaje,idCurso'];
 
    public $timestamps = false;

}
