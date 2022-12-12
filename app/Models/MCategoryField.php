<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MCategoryField extends Model
{
    protected $table = "m_category_fields";
    public $timestamps = false;
    use HasFactory;

    protected $fillable = ["name", "title", "subtitle", "style","note", "note_style", "required", "value", "styles", "position", "visible", "is_detail", "m_field_id", "m_category_id","m_attr_id", "opt"];

    public function getFieldAttribute(){
        return $this->m_attr_id ? "_{$this->m_attr_id}": "c{$this->id}";
    }
    protected $appends = ['field'];
    public function getElements($data){
        $opt = json_decode($this->opt, true)??[];
        $countries =
        $domains =
        $emails =
        $elements = [];
        $t = $opt["t"];
        if($t == 0){
            $v = $opt["v"]??[];

            foreach($v as $x){
                array_push($elements,(object)["id"=>$x[1], "name"=>$x[0]]);
            }
        }elseif($t == 1)
            $elements = $data['doctypes'] ?? [];
        elseif($t == 2)
            $elements = $data['groups'] ?? [];
        elseif($t == 3 )
            $elements = $data['countries'] ?? [];
        elseif($t == 4 )
            $elements = $data['departments'] ?? [];
        elseif($t == 6)
            $elements = $data['emails'] ?? [];
        elseif($t == 7 )
            $elements = $data['domains'] ?? [];
        return $elements;
    }
}
