<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

use App\Models\Evento;

class EventCalendarController extends Controller
{
    //
    public function index(){
        if(Session::get('usuario') && Session::get('usuario')!='operador'){
            /*if($r->ajax()){
                $data= Evento::whereDate('start', '>=', $r->start)
                    ->whereDate('end', '<=', $r->end)
                    ->get(['groupId', 'title', 'start', 'end', 'allDay', 'backgroundColor', 'borderColor']);

                return response()->json($data);
            }*/

            return view('Administrador.Eventos.evento');
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE REGISTRA EVENTOS EN LA BD
    public function registro_eventos(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $fdesde = $r->input('R_fechaI');
            $fhasta = $r->input('R_fechaH');
            $resfhasta= $fhasta;
            $titulo= $r->input('inputTituloEvent');
            $descripcion= $r->input('ndescripcion');
            $tipoevento= $r->input('tipoevento');
            $LAST_ID = '';

            $fhasta= date("Y-m-d",strtotime($fhasta."- 1 days"));
            $date= now();

            $tipoImg='';

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filerealpath= $file->getRealPath();
                //$filesize= $file->getSize();
                $data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];

                if($width<=1300 && $height > 1550){
                    $tipoImg='rectangular';
                }else{
                    $tipoImg='cuadrado';
                }
                //return  response()->json(['resultado'=> 'w: '.$width.' h: '.$height.' tipoimg: '.$tipoImg]);

                if($fileextension== $this->validarImg($fileextension)){
                    $storeimg= Storage::disk('img_eventos')->put($filename,  \File::get($file));
                    if($storeimg){
                        /*$sql_insert = DB::connection('mysql')->insert('insert into tab_eventos (
                            desde, hasta, titulo, descripcion, imagen, created_at
                        ) values (?,?,?,?,?,?)', [$fdesde, $fhasta, $titulo, $descripcion, $filename, $date]);*/
                        $sql_insert= DB::connection('mysql')->table('tab_eventos')->insertGetId(
                            ['desde'=> $fdesde, 'hasta'=> $fhasta, 'titulo'=> $titulo, 'descripcion'=> $descripcion,
                            'imagen'=> $filename, 'tipo'=> $tipoevento, 'formaimg'=> $tipoImg, 'created_at'=> $date]
                        );
                        $LAST_ID= $sql_insert;
                        if($sql_insert){
                            $color= $this->setBackgroundColor();
                            return response()->json(["resultado"=> true, "ID"=>$LAST_ID, "titulo"=>$titulo, 
                            "desde"=> $fdesde, "hasta"=>$resfhasta, "color"=> $color]);
                        }else{
                            return response()->json(['resultado'=>false]);
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'noimagen']);
                }
            }
        }else{
            return response()->json(['resultado'=> 'noimagen']);
        }
    }

    //FUNCION QUE ESTABLECE EL BACKGROUND DEL EVENTO EN EL CALENDARIO
    private function setBackgroundColor(){
        $arrayColor= array('#f56954','#f39c12','#0073b7','#00c0ef','#00a65a','#3c8dbc', '#DC5E97', '#3DDB95', '#AF45F4', 
            '#1DDE23', '#DE1DD8', '#DE721D', '#1ABC9C', '#CB4335', '#AAB7B8', '#5DADE2', '#1DC4DE', '#EF4C1C', '#00ACC1', '#8BC34A');
        $longi= sizeof($arrayColor);
        $pos= rand(0, ($longi-1));
        return $arrayColor[$pos];
    }

    //FUNCION QUE VALIDA SI ES UNA IMAGEN
    private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE RETORNA TODOS LOS EVENTOS EN EL CALENDARIO
    public function get_eventos(){
        $dato= array();
        $sql_eventos= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')->get();
        
        foreach($sql_eventos as $ev){
            $fhs= $ev->hasta;
            //$hasta= date("Y-m-d",strtotime($fhs."+ 1 days"));
            $hasta= Carbon::createFromFormat('Y-m-d', $fhs)->addDay()->toDateTimeString();
            $color= $this->setBackgroundColor();
            $dato[]=array('groupId'=> $ev->id, 'start'=> $ev->desde, 'end'=> $hasta, 'title'=> $ev->titulo, 
            'allDay'=>true, 'backgroundColor'=> $color, 'borderColor'=>$color);
            $color='';
        }

        return response()->json($dato);
    }

    //FUNCION QUE RETORNA INFORMACION DEL EVENTO SELECCIONADO EN EL CALENDARIO
    public function get_evento_select(Request $r){
        $dato= array();
        $sql_eventos= DB::connection('mysql')->table('tab_eventos')->where('id', $r->id)->get();
        
        foreach($sql_eventos as $ev){
            $dato[]=array('groupId'=> $ev->id, 'start'=> $ev->desde, 'end'=> $ev->hasta, 'title'=> $ev->titulo, 
            'descripcion'=> $ev->descripcion, 'imagen'=> $ev->imagen, 'tipo'=> $ev->tipo);
        }

        return response()->json($dato);
    }

    //FUNCION PARA INACTIVAR/ACTIVAR UN EVENTO
    public function inactivar_eventos(Request $r){
        $id= $r->id;
        $estado= $r->estado;
        $date= now();

        $sql_update= DB::table('tab_eventos')
        ->where('id', $id)
        ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    private function get_img_event($id){
        $result= DB::connection('mysql')->select('SELECT imagen FROM tab_eventos WHERE id=?', [$id]);

        $dato='';
        foreach ($result as $key) {
            $dato= $key->imagen;
        }

        //Storage::disk('your_disk')->delete('file.jpg'); // delete file from specific disk e.g; s3, local etc

        return $dato;
    }

    //FUNCION PARA ACTUALIZAR EVENTOS
    public function actualizar_eventos(Request $r){
        $id= $r->input('id_agenda');
        $fdesde = $r->input('txt_fechaI');
        $fhasta = $r->input('txt_fechaH');
        $titulo= $r->input('txtTituloEv');
        $descripcion= $r->input('ndescripcion');
        $opcion= $r->input('opcion');
        $tipoevento= $r->input('tipoevento');
        $date= now();

        if($opcion=="conimagen"){
            $sql_update= DB::table('tab_eventos')
            ->where('id', $id)
            ->update(['desde' => $fdesde, 'hasta' => $fhasta, 'titulo' => $titulo, 'descripcion' => $descripcion, 
                'tipo'=> $tipoevento, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else if($opcion=="nuevaimagen"){
            if ($r->hasFile('fileedit')) {
                $tipoImg='';
                $files  = $r->file('fileedit'); //obtengo el archivo
                foreach($files as $file){
                    $filename= $file->getClientOriginalName();
                    $fileextension= $file->getClientOriginalExtension();
                    $data = getimagesize($file->getRealPath());
                    $width = $data[0];
                    $height = $data[1];

                    if($width<=1300 && $height > 1550){
                        $tipoImg='rectangular';
                    }else{
                        $tipoImg='cuadrado';
                    }

                    if($fileextension== $this->validarImg($fileextension)){
                        $storeimg= Storage::disk('img_eventos')->put($filename,  \File::get($file));
                        if($storeimg){
                            $sql_update= DB::table('tab_eventos')
                            ->where('id', $id)
                            ->update(['desde' => $fdesde, 'hasta' => $fhasta, 'titulo' => $titulo, 
                                'descripcion' => $descripcion, 'imagen'=> $filename, 'tipo'=> $tipoevento, 
                                'formaimg'=> $tipoImg, 'updated_at'=> $date]);

                            if($sql_update){
                                return response()->json(['resultado'=> true]);
                            }else{
                                return response()->json(['resultado'=> false]);
                            }
                        }else{
                            return response()->json(["resultado"=>"nocopy"]);
                        }
                    }else{
                        return response()->json(['resultado'=> 'noimagen']);
                    }
                }
            }else{
                return response()->json(['resultado'=> 'noimagen']);
            }
        }
    }
}
