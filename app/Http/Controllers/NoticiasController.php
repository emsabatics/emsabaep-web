<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class NoticiasController extends Controller
{
    //FUNCION QUE DESPLIEGA LA INTERFAZ DE REGISTRAR NOTICIA
    public function registrar_noticia(){
        if(Session::get('usuario') && Session::get('usuario')!='operador'){
            return view('Administrador.Noticias.registrar_noticia');
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE DESPLIEGA LA INTERFAZ DE ACTUALIZAR NOTICIA
    public function actualizar_noticia($id){
        if(Session::get('usuario') && Session::get('usuario')!='operador'){
            $id= base64_decode($id);
            $estado='1';
            $sqltexto = DB::connection('mysql')->select('SELECT * FROM tab_noticias WHERE id=?', [$id]);
            $sqlimg= DB::connection('mysql')->select('SELECT id, imagen FROM tab_img_noticias WHERE id_noticia=? AND estado=?', [$id, $estado]);
            return view('Administrador.Noticias.actualizar_noticia', ['texto'=> $sqltexto, 'imagen'=> $sqlimg]);
        }else{
            return redirect('/login');
        }
    }

    public function store_noticia(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo

            $lugar= $r->lugar;
            $titulo= $r->titulo;
            $descpshort= $r->descripcioncorta;
            $descripcion= $r->descripcion;
            $fecha= $r->fecha;
            $hashtag= $r->hashtag;
            $num_img= $r->num_img;
            $date= now();
            $hora= $date->format('H:i:s');
            $LAST_ID = '';

            $cont_noformat=0;

            $sql_insert = DB::connection('mysql')->table('tab_noticias')->insertGetId(
                ['lugar'=> $lugar, 'titulo'=> $titulo, 'descripcion_corta'=> $descpshort, 'descripcion'=>$descripcion,
                'hashtag'=>$hashtag, 'fecha'=>$fecha, 'hora'=> $hora, 'created_at'=> $date]
            );
            $LAST_ID= $sql_insert;
            $contar=0;
            if($sql_insert){
                //return response()->json(["resultado"=> true]);
                foreach($files as $file){
                    // here is your file object
                    $filename= $file->getClientOriginalName();
                    $fileextension= $file->getClientOriginalExtension();
                    //$filesize= $file->getSize();//
                    $data = getimagesize($file->getRealPath());
                    $width = $data[0];
                    $height = $data[1];
    
                    if($fileextension== $this->validarImg($fileextension)){
                            $storeimg= Storage::disk('img_noticias')->put($filename,  \File::get($file));
                            if($storeimg){
                                $sql_insert_img= DB::connection('mysql')->table('tab_img_noticias')->insertGetId(
                                    ['id_noticia'=> $LAST_ID, 'imagen'=> $filename]
                                );
                                if($sql_insert_img){
                                    $contar++;
                                }
                            }else{
                                return response()->json(['resultado'=>false]);
                            }
                        /*if($width < 1900 && $height < 1080){
                            $cont_noformat++;
                        }else{
                            $storeimg= Storage::disk('img_noticias')->put($filename,  \File::get($file));
                            if($storeimg){
                                $sql_insert_img= DB::connection('mysql')->table('tab_img_noticias')->insertGetId(
                                    ['id_noticia'=> $LAST_ID, 'imagen'=> $filename,  'created_at'=> $date]
                                );
                                if($sql_insert_img){
                                    $contar++;
                                }
                            }else{
                                return response()->json(['resultado'=>false]);
                            }
                        }*/
                    }else{
                        return response()->json(['resultado'=> 'noimagen']);
                    }
                }

                if($contar==$num_img){
                    return response()->json(["resultado"=> true]);
                }else{
                    return response()->json(["resultado"=> false]);
                }
            }else{
                return response()->json(["resultado"=> false]);
            }
        }else{
            return response()->json(['resultado'=> 'noimagen']);
        }
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


    public function list_noticias(){
        if(Session::get('usuario') && Session::get('usuario')!='operador'){
            $dato = array();
            $mariaDbec= DB::connection('mysql')->table('tab_noticias')->orderBy('fecha', 'desc')->get();
            foreach ($mariaDbec as $k) {
                $idnoticia= $k->id;
                $fecha= $this->setFecha($k->fecha);
                $numpics= $this->getNumPics($idnoticia);
                $dato[]=array('id'=> $idnoticia, 'lugar'=>$k->lugar, 'titulo'=>$k->titulo, 'descripcion_corta'=> $k->descripcion_corta,
                'descripcion'=> $k->descripcion, 'fecha'=> $fecha, 'estado'=> $k->estado, 'num_fotos'=> $numpics);
            }
            //['datos'=> json_encode($dato)]
            return view('Administrador.Noticias.listado_noticia', ['datos'=> json_encode($dato)]);
        }else{
            return redirect('/login');
        }
    }

    private function formatDia($day)
    {
        $dia = "";
        switch ($day) {
            case "Sunday":
                $dia = "Domingo";
                break;
            case "Monday":
                $dia = "Lunes";
                break;
            case "Tuesday":
                $dia = "Martes";
                break;
            case "Wednesday":
                $dia = "Miércoles";
                break;
            case "Thursday":
                $dia = "Jueves";
                break;
            case "Friday":
                $dia = "Viernes";
                break;
            case "Saturday":
                $dia = "Sábado";
                break;
        }
        return $dia;
    }

    private function setFecha($date){
        $arraymes= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre',
        'Noviembre','Diciembre');
        $anio= substr($date, 0, 4);
        $mes= substr($date,-5,2);
        $dia= substr($date, 8, strlen($date));
    
        $mes= intval($mes);
        $diaN= $this->formatDia(date('l', strtotime($date)));
        //return $dia.' de '.$arraymes[$mes].' del '.$anio;
        return $diaN.', '.$dia.' de '.$arraymes[$mes].' del '.$anio;
    }

    private function getNumPics($idn){
        $estado="1";
        $resultado= 0;

        $sql= DB::connection('mysql')->select('SELECT COUNT(*) as total FROM tab_img_noticias WHERE id_noticia=? AND estado=?',[$idn,$estado]); 

        foreach($sql as $r){
            $resultado= $r->total;
        }

        return $resultado;
    }

    public function actualizar_noticia_texto(Request $r){
        $id= $r->id;
        $lugar= $r->lugar;
        $titulo= $r->titulo;
        $descpshort= $r->descripcioncorta;
        $descripcion= $r->descripcion;
        $fecha= $r->fecha;
        $hashtag= $r->hashtag;
        $num_img= $r->num_img;
        $date= now();

        $sql_update= DB::table('tab_noticias')
            ->where('id', $id)
            ->update(['lugar'=> $lugar, 'titulo'=> $titulo, 'descripcion_corta'=> $descpshort, 'descripcion'=>$descripcion,
            'hashtag'=>$hashtag, 'fecha'=>$fecha, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function actualizar_noticia_img(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $id= $r->input('idnoticiapics');
            $num_img= $r->num_img;
            $date= now();
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filesize= $file->getSize();//
                $data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];

                if($fileextension== $this->validarImg($fileextension)){
                    $storeimg= Storage::disk('img_noticias')->put($filename,  \File::get($file));
                    if($storeimg){
                        $sql_insert_img= DB::connection('mysql')->table('tab_img_noticias')->insertGetId(
                            ['id_noticia'=> $id, 'imagen'=> $filename]
                        );
                        if($sql_insert_img){
                            $contar++;
                        }
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                    /*if($width < 1900 && $height < 1080){
                        $cont_noformat++;
                    }else{
                        $storeimg= Storage::disk('img_noticias')->put($filename,  \File::get($file));
                        if($storeimg){
                            $sql_insert_img= DB::connection('mysql')->table('tab_img_noticias')->insertGetId(
                                ['id_noticia'=> $LAST_ID, 'imagen'=> $filename,  'created_at'=> $date]
                            );
                            if($sql_insert_img){
                                $contar++;
                            }
                        }else{
                            return response()->json(['resultado'=>false]);
                        }
                    }*/
                }else{
                    return response()->json(['resultado'=> 'noimagen']);
                }
            }

            if($contar==$num_img){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    public function inactivar_img_noticia(Request $r){
        $id= $r->id;
        $estado= $r->estado;
        $idnoticia= $r->idnoticia;

        $sql_update= DB::table('tab_img_noticias')
            ->where('id', $id)
            ->update(['estado'=> $estado]);
        
        if($sql_update){
            $imagen= $this->getImagen($id);
            $conimg= $this->getcountImagen($imagen);
            $numpics= $this->getNumPics($idnoticia);
            if($conimg==1){
                if (Storage::disk('img_noticias')->exists($imagen)) {
                    Storage::disk('img_noticias')->delete($imagen);
                }
            }
            return response()->json(["resultado"=> true, "numimg"=> $numpics]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    private function getImagen($id){
        $sql= DB::connection('mysql')->select('SELECT imagen FROM tab_img_noticias WHERE id=?', [$id]);
        $resultado= 0;
        foreach($sql as $r){
            $resultado= $r->imagen;
        }
        return $resultado;
    }

    private function getcountImagen($imagen){
        $sql= DB::connection('mysql')->select('SELECT COUNT(imagen) as total FROM tab_img_noticias WHERE imagen=?', [$imagen]);
        $resultado= 0;
        foreach($sql as $r){
            $resultado= $r->total;
        }
        return $resultado;
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA NOTICIAS
    public function inactivar_noticia(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        $sql_update= DB::table('tab_noticias')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
