<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MProductIns extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $guarded = ["id"];

    protected $appends = ['d'];

    public function getDAttribute(){
        return json_decode($this->data, true);
    }

    public function formatValue($campo, $data = []){
        $v = $this->d;
        $value = $v[$campo->field];
        if (count($data)>0) {
            $doctypes = $data['doctypes'] ?? [];
            $groups = $data['groups'] ?? [];
            $countries = $data['countries'] ?? [];
            $domains = $data['domains'] ?? [];
            $emails = $data['emails'] ?? [];
            $opt = json_decode($campo->opt, true)??[];
            if($campo->m_field_id == 11){
                $vv = "";
                $t = $opt["t"];
                if($t == 0){
                    $v = $opt["v"]??[];
                    foreach($v as $x){
                        if($x[1] == $value)$vv = $x[0];
                    }
                }elseif($t == 1)
                    $vv = $doctypes?->firstWhere("id",$value)?->name??"";
                elseif($t == 2)
                    $vv = $groups?->firstWhere("id",$value)?->codigo??"";
                if($vv)$value=$vv;
            }
        }
        return $value;
    }
}
