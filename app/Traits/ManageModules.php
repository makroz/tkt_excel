<?php
namespace App\Traits;

use App\Departamento;
use App\Emails;
use App\Models\MCategoryField;
use App\Models\MProduct;
use App\Models\MProductIns;
use App\TipoDoc;

trait ManageModules {
    public function getDepartaments(){
        return Departamento::select('ubigeo_id','nombre','nombre as id','nombre as name' )
            ->whereIn('ubigeo_id', ['01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25'])
            ->get();
    }
    public function getValuesElements()
    {
        $doctypes = TipoDoc::select(["id", "tipo_doc as name"])->get();
        $groups = \DB::table('est_grupos')->select(["id", "codigo as code", "nombre as name"])->get();
        $countries = \DB::table('country')->select('name', 'name as id','phonecode','nicename')->get();
        $domains = \DB::table('tb_email_permitos')->select(["id", "nombre as name", "dominio as domain"])->get();
        $emails = Emails::select(["id", "nombre as name", "email"])->orderBy("nombre",'asc')->get();
        $departments = $this->getDepartaments();
        return compact("doctypes", "groups", "countries", "domains", "emails", "departments");
    }

    public function getRecIns($m_category_id)
    {
        $campos = MCategoryField::where("m_category_id", $m_category_id)->orderBy("position")->get();
        $recs = $campos->where("is_detail", 0)??[];
        $ins = $campos->where("is_detail", 1)??[];
        return [$recs, $ins];
    }
    public function getProductArrayData($id){
        $data = [];
        if($id>0){
            $d = MProduct::find($id)->toArray()?? [];
            $data = count($d) > 0 ? json_decode($d["data"], true) : [];
        }
        return $data;
    }
    public function getInsArrayData($m_product_id, $id){
        $data = [];
        if($id>0){
            $d = MProductIns::firstWhere(compact("m_product_id", "id"))->toArray()?? [];
            $data = count($d) > 0 ? json_decode($d["data"], true) : [];
        }
        return $data;
    }


    public function inputsForSave($recs, $inputs, $request, $m_category_id, $product_data, $id)
    {
        $data = [];
        $if = 0;
        foreach($recs as $c){
            $m_field_id = $c["m_field_id"];
            $visible = $c["visible"];
            $m_attr_id = $c["m_attr_id"];
            $m_field = $c["field"];
            $_id = $c["id"];// = $m_attr_id

            $value = array_key_exists($_id, $inputs)?$inputs[$_id]:"";
            if($m_field_id == 3){//Int
                $value0 = intval($value);
                $value1 = floatval($value);
                $value = ($value0 == $value1) ? $value0: $value1;
            }
            elseif($m_field_id == 7){//Fecha
                $value2 = intval(preg_replace('/(\d{2})[\/-]?(\d{2})[\/-]?(\d{4})/', "$3$2$1", $value));
                if($value2>0)$value = $value2;
            }
            elseif($m_field_id == 15){//File
                if($file = $request->file("inputs.{$_id}")){
                    $path = "images/m_{$m_category_id}";
                    $valueOld = array_key_exists($m_field, $product_data)?$product_data[$m_field]:"";
                    if($valueOld!="" && file_exists("{$path}/{$valueOld}"))
                        unlink("{$path}/{$valueOld}");
                    //$name = $file->getClientOriginalName();
                    $name = $m_category_id.'_'.strtotime('now').$if.'.'.$file->getClientOriginalExtension();
                    $file->move( $path,$name);
                    $value = $name;
                    $if++;
                }else{//value anterior
                    if($id>0)
                        $value = array_key_exists($m_field, $product_data)?$product_data[$m_field]:"";
                }
            }
            $data[$m_field] = $value;
        }
        return $data;
    }

    public function getImageData($product_data, $m_category_id, $field, $path="")
    {
        if($path=="")$path = "images/m_{$m_category_id}";
        $img = array_key_exists($field, $product_data)?$product_data[$field]:"";
        return  $img!="" && file_exists("{$path}/{$img}") ? asset("{$path}/{$img}") :"";
    }

}
