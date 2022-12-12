<?php

namespace App\Http\Controllers;

use App\Evento;
use Jenssegers\Date\Date;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Ajuste;
use App\AccionesRolesPermisos;
use App\Campanias;

use Alert;
use Auth, Cache, File;
use Illuminate\Support\Facades\DB;
use  Excel;

class AjustesController extends Controller
{
  public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request){
      $this->actualizarSesion();
        //VERIFICA SI TIENE EL PERMISO
        if(!isset( session("permisosTotales")["participantes"]["permisos"]["reportes"]   ) ){
            Auth::logout();
            return redirect('/login');
        }

        $campanias_data = Campanias::all();

        ////PERMISOS
        $roles = AccionesRolesPermisos::getRolesByUser(\Auth::User()->id);
        $permParam["modulo_alias"] = "participantes";
        $permParam["roles"] = $roles;
        $permisos = AccionesRolesPermisos::getPermisosByRolesController($permParam);
        ////FIN DE PERMISOS

        $tipo = $request->get('tipo','')??'';
        $fecha1 = $request->get('fecha1','')??'';
        $fecha2 = $request->get('fecha2','')??'';

        $data = $this->getDataRep(compact("tipo","fecha1","fecha2"));
        $tipos = ["","EVENTOS","CAII","MAESTRÍA","MAILING"];
        $titulo = $tipos[$tipo]??"";


        return view('reportes.reportes_modulos', compact('data','permisos','fecha1','fecha2','tipo','titulo'));
        
        /* if($request->get('s')){
            Cache::flush();
            $search = $request->get('s');

            $campanias_data = Campanias::where("nombre", "LIKE", '%'.$search.'%')
                //->orWhere("descripcion", "LIKE", '%'.$search.'%')
                //->orWhere("uo_solicitante", "LIKE", '%'.$search.'%')
                ->orderBy('id', request('sorted', 'DESC'))
                ->paginate(15);

        }else{
            
            //$key = 'campania.page.'.request('page', 1);
            //$campanias_data = Cache::rememberForever($key, function(){
                //return Campanias::orderBy('id', request('sorted', 'DESC'))
                    //->paginate(15);

            //});
            //$campanias_data = Campanias::orderBy('id', request('sorted', 'DESC'))
                //->paginate(15);
        }
        //$campanias_data = Campanias::all();
        $camps = $campanias_data; */

      return view('reportes.reportes_modulos', compact('camps','permisos'));
    }

    public function excel (Request $request){
        $fileName = "regiustrados";
        $tipo = $request->get('tipo','')??'';
        $fecha1 = $request->get('fecha1','')??'';
        $fecha2 = $request->get('fecha2','')??'';
        $data = $this->getDataRep(compact("tipo","fecha1","fecha2"));

        Excel::create($fileName, function($excel) use ($request,$data, $tipo) {
            $sheetName = "";
            if($tipo==1)$sheetName = "EVENTOS";
            if($tipo==2)$sheetName = "CAII";
            if($tipo==3)$sheetName = "MAESTRÍA";
            if($tipo==4)$sheetName = "MAILING";

            $excel->sheet($sheetName, function($sheet) use($data, $tipo) {
                $cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
                //if($tipo==2)$cols= ["#","Nombre","Registrados","Asistidos","Fecha Inicio","Fecha Fin","Gafete"];
                if($tipo==3)$cols= ["#","Nombre","Registrados","Aptos Examen","Aprobaron Examen","Fecha Inicio","Fecha Fin"];
                if($tipo==4)$cols= ["#","Nombre","Total Participantes","Total Enviados","Total Rebotados","Fecha"];
                $fila = 1;
                $sheet->row($fila, $cols);
                $rows= $data["data"]??array();
                //$fila++;
                if(count($rows)>0)
                    foreach ($rows as $v)
                        if($tipo==1||$tipo==2)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["registrados"],$v["asistieron"],$v["fecha"],$v["fecha2"],$v["gafete"]] );
                        elseif($tipo==3)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["registrados"],$v["aptos"],$v["aprobados"],$v["fecha"],$v["fecha2"]] );
                        elseif($tipo==4)$sheet->row(++$fila, [$v["id"],$v["nombre"],$v["participantes"],$v["entregados"],$v["rebotados"],$v["fecha"]] );
            });
        })->export('xlsx');;
    }
    
    function getDataRep($data){

        $tipo = $data["tipo"];
        $fecha1 = $data["fecha1"];
        $fecha2 = $data["fecha2"];
        $f1 = $f2 = "";
        $colIni = "fechai_evento";
        $colFin = "fechaf_evento";
        if($tipo==4){
            $colIni = "created_at";
            $colFin = "created_at";
            $q = Campanias::select("id","nombre","total","enviados","errores","created_at");
        }else{
            $q = Evento::select("id","nombre_evento","fechai_evento","fechaf_evento","gafete","gafete_html");
        }

        if($fecha1!="")
            $f1 = Carbon::createFromFormat('d/m/Y', $fecha1)->format('Y-m-d 00:00:00');
        if($fecha2!="")
            $f2 = Carbon::createFromFormat('d/m/Y', $fecha2)->format('Y-m-d 00:00:00');
        if($f1!=''||$f2!=''){
            if($f1!=''&&$f2!='')$q->where($colIni, '<=', $f1)->where($colFin, '>=', $f2);
            elseif($f1!='')$q->where($colIni,  $f1);
            else $q->where($colFin, '<=', $f2);
        }

        $ids = [];
        $data = false;
        $details = [];
        if($tipo>0&&$tipo!=4){
            if($tipo==2)$q->where('eventos_tipo_id',1);
            if($tipo==1)$q->where('eventos_tipo_id',2);
            if($tipo==4)$q->where('eventos_tipo_id',4);
            if($tipo==3)$q->where('eventos_tipo_id',4);
            if($tipo==8)$q->where('eventos_tipo_id',8);
            $eventos = $q->get();
            $evento_count = count($eventos);
            $total_gafete = 0;
            $total_sin_gafete = 0;
            if($evento_count>0){
                foreach ($eventos as $evento){
                    $id = $evento->id;
                    $fecha1 = $evento->fechai_evento?$evento->fechai_evento->format("d-m-Y"):'';
                    $fecha2 = $evento->fechaf_evento?$evento->fechaf_evento->format("d-m-Y"):'';
                    $gafete = isset($evento->gafete)&&$evento->gafete_html!=''?1:0;
                    if($gafete==1)$total_gafete++;$total_sin_gafete++;
                    $d = ["id"=>$id,"nombre"=>$evento->nombre_evento,"gafete"=>$gafete,
                        "fecha"=>$fecha1,"fecha2"=>$fecha2,"gafete"=>$gafete==1?'SI':'NO',
                        "registrados"=>0,"asistieron"=>0,"aptos"=>0,"aprobados"=>0];
                    $details[$id] = $d;
                    array_push($ids,intval($id));
                }
            }
            $colsMaestria = '';
            if($tipo==3)
                $colsMaestria = ", sum(if(confirmado=1,1,0)) AS aptos, sum(if(actividades_id=0,0,1)) AS aprobados";

            $registrados = DB::table('estudiantes_act_detalle')->
            selectRaw("eventos_id as id, count(estudiantes_id) AS can{$colsMaestria}")->whereIn('eventos_id', $ids)->groupBy('eventos_id')->get();
            $registrado_count = count($registrados);
            if($registrado_count>0)
                foreach ($registrados as $d)
                    if(isset($details[$d->id])){
                        $details[$d->id]["registrados"] = $d->can;
                        if($tipo==3){
                            $details[$d->id]["aptos"] = $d->aptos;
                            $details[$d->id]["aprobados"] = $d->aprobados;
                        }
                    }
            $asistieron = DB::table('asistencia_eventos')->
            selectRaw('evento_id as id, count(estudiantes_id) AS can')->whereIn('evento_id', $ids)->groupBy('evento_id')->get();
            $asistieron_count = count($asistieron);
            if($asistieron_count>0)
                foreach ($asistieron as $d)
                    if(isset($details[$d->id]))$details[$d->id]["asistieron"] = $d->can;
            $data = ["total_gafete"=>$total_gafete,"total_sin_gafete"=>$total_sin_gafete,"data"=>$details,"count"=>$evento_count];
        }
        if($tipo==4){
            $campanias = $q->get();
            $campania_count = count($campanias);
            $total = $rebotados = $entregados = 0;
            if($campania_count>0){
                foreach ($campanias as $campania){
                    $id = $campania->id;
                    $total += $campania->total;
                    $entregados += $campania->enviados;
                    $rebotados += $campania->errores;
                    $fecha = $campania->created_at?$campania->created_at->format("d-m-Y"):'';
                    $d = ["id"=>$id,"nombre"=>$campania->nombre,"fecha"=>$fecha,
                        "participantes"=>$campania->total,"entregados"=>$campania->enviados,"rebotados"=>$campania->errores
                    ];
                    $details[$id] = $d;
                    array_push($ids,intval($id));
                }
            }
            $data = array("data"=>$details,"total"=>$total,"entregados"=>$entregados,"rebotados"=>$rebotados,"count"=>$campania_count);
        }
        return $data;
    }

    public function edit($id)
    {

        $datos = Ajuste::findOrFail($id);
        return view('ajustes.edit',compact('datos'));
    }


    public function update(Request $request, $id)
    {

        try {
              $url_img = public_path('images/form/a/');

              $ajustes = Ajuste::find($id);

              if($file = $request->file('logo')){

                $img = Ajuste::findOrFail($id);
                $img_borrar = $url_img.$img->logo;
                File::delete($img_borrar);

                //$nombre = $file->getClientOriginalName();
                $nombre = 'logo_'.strtotime('now').'.'.$file->getClientOriginalExtension();
                $file->move('images/form/a',$nombre);

                $ajustes->logo = $nombre;
              }

               $ajustes->email = $request->input('email');
               $ajustes->email_nom = $request->input('email_nom');
               $ajustes->save();

            Cache::flush();

            alert()->success('Mensaje Satisfactorio','Registro actualizado.');

            return redirect()->back();

        } catch (Exception $e) {
          return "Error: ".$e;

        }
    }



}
