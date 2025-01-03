<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class DepartamentoController extends Controller
{
    //FUNCION CARGA INFORMACION SOBRE LA INTERFAZ DE DEPARTAMENTOS
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $resultado= array();
            //$array_resultado= DB::table('tab_info_each_departamento')->orderBy('tipo_dep')->get();
            //$groupmision= $array_resultado->groupBy('tipo_dep');
            $array_resultado = DB::connection('mysql')->select('SELECT * FROM tab_info_each_departamento ORDER BY tipo_dep DESC');
            foreach($array_resultado as $data){
                $tipo= $data->tipo_dep;
                $res='';
                if($tipo=='gerencia'){
                    $id_dep= $data->id_gerencia;
                    $res_table_in= DB::connection('mysql')->select('SELECT nombre FROM tab_gerencia_dep WHERE id=?', [$id_dep]);
                    foreach($res_table_in as $d){
                        $res= $d->nombre;
                    }
                }else if($tipo=='direccion'){
                    $id_dep= $data->id_direccion;
                    $res_table_in= DB::connection('mysql')->select('SELECT nombre FROM tab_direccion_dep WHERE id=?', [$id_dep]);
                    foreach($res_table_in as $d){
                        $res= $d->nombre;
                    }
                }else if($tipo=='coordinacion'){
                    $id_dep= $data->id_coordinacion;
                    $res_table_in= DB::connection('mysql')->select('SELECT nombre FROM tab_coordinacion_dep WHERE id=?', [$id_dep]);
                    foreach($res_table_in as $d){
                        $res= $d->nombre;
                    }
                }

                $resultado[] = array('id'=> $data->id, 'responsable'=> $data->responsable, 'email'=> $data->email, 'telefono'=> $data->telefono,
                            'extension'=> $data->extension, 'nombre_dep'=> $res, 'estado'=> $data->estado);
            }
            return response()->view('Administrador.Infor.departamentos', ['resultado' => $resultado]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION CARGA INFORMACION SOBRE LA INTERFAZ DE REGISTRAR DEPARTAMENTOS
    public function add_departamento(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $resultado= array();
            $array_gerencia= array();
            $array_direccion= array();

            //$array_gerencia = DB::table('tab_gerencia_dep')->where('estado','1')->get();
            $array_gerencia = DB::table('tab_gerencia_dep')->get();
            $array_direccion = DB::connection('mysql')->select('SELECT tab_dir.id, tab_dir.nombre, tab_ger.nombre as dependencia, tab_dir.estado FROM tab_direccion_dep as tab_dir, tab_gerencia_dep as tab_ger WHERE tab_dir.id_gerencia=tab_ger.id');
            $coordinacion1 = DB::connection('mysql')->select('SELECT C.id, C.nombre, G.nombre as dependencia, C.estado FROM tab_coordinacion_dep as C JOIN tab_gerencia_dep as G ON C.id_gerencia= G.id');
            $coordinacion2 = DB::connection('mysql')->select('SELECT C.id, C.nombre, D.nombre as dependencia, C.estado FROM tab_coordinacion_dep as C JOIN tab_direccion_dep as D ON C.id_direccion= D.id');

            $array_coordinacion= array();
            foreach($coordinacion1 as $data){
                $array_coordinacion[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'dependencia'=> $data->dependencia, 'estado'=> $data->estado);
            }

            foreach($coordinacion2 as $data){
                $array_coordinacion[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'dependencia'=> $data->dependencia, 'estado'=> $data->estado);
            }
            //$groupmision= $estructura->groupBy('tipo');
            return response()->view('Administrador.Infor.registrar_departamento', ['gerencia' => $array_gerencia, 'direccion'=> $array_direccion, 'coordinacion'=> $array_coordinacion]);
        }else{
            return redirect('/login');
        }
    }
    
    //FUNCION QUE ABRE INTERFAZ DE REGISTRO DE INFO DEPARTAMENTO
    public function add_info_departamento(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            return response()->view('Administrador.Infor.registrar_info_depar');
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE RETORNA INFO DE LOS DEPARTAMENTOS GERENCIA/DIRECCION COMO DEPENDENCIAS
    public function get_departamento($tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //return response()->json(['tipo'=>$tipo]);
            if($tipo=='direccion'){
                $gerencia= DB::table('tab_gerencia_dep')->where('estado','1')->get();
                $array_resultado= array();
                foreach($gerencia as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'gerencia');
                }

                return response()->json(['array'=>$array_resultado]);
            }else if($tipo=='coordinacion'){
                $gerencia= DB::table('tab_gerencia_dep')->where('estado','1')->get();
                $direccion = DB::table('tab_direccion_dep')->where('estado','1')->get();
                $array_resultado= array();
                foreach($gerencia as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'gerencia');
                }

                foreach($direccion as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'direccion');
                }

                return response()->json(['array'=>$array_resultado]);
            }
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE RETORNA INFORMACION DE LAS TABLAS GERENCIA/DIRECCION/COORDINACION
    //SEGUN LO SELECCIONADO EN LA INTERFAZ REGISTRO INFO DEPARTAMENTO
    public function get_info_departamento($tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //return response()->json(['tipo'=>$tipo]);
            if($tipo=='gerencia'){
                $gerencia= DB::table('tab_gerencia_dep')->where('estado','1')->get();
                $array_resultado= array();
                foreach($gerencia as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'gerencia');
                }
                return response()->json(['array'=>$array_resultado]);
            }else if($tipo=='direccion'){
                $direccion = DB::table('tab_direccion_dep')->where('estado','1')->get();
                $array_resultado= array();
                foreach($direccion as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'direccion');
                }
                return response()->json(['array'=>$array_resultado]);
            }else if($tipo=='coordinacion'){
                $coordinacion= DB::table('tab_coordinacion_dep')->where('estado','1')->get();
                $array_resultado= array();
                foreach($coordinacion as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'coordinacion');
                }
                return response()->json(['array'=>$array_resultado]);
            }
        }else{
            return redirect('/login');
        }
    }

    //FUNCIÓN QUE DESPLIEGA INFORMACIÓN DE LOS DEPARTAMENTOS PARA EDITAR INFORMACION DEL DEPARTAMENTO SELECCIONADO
    public function get_depar_indi($tipo, $id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            if($tipo=='gerencia'){
                $gerencia= DB::table('tab_gerencia_dep')->where('id', $id)->get();
                $array_resultado= array();
                foreach($gerencia as $data){
                    $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'imagen'=> $data->imagen, 'tipo'=> 'gerencia');
                }
                return response()->json($array_resultado);
            }else if($tipo=='direccion'){
                $array_resultado= array();
                $direccion= DB::connection('mysql')->select('SELECT d.id, d.nombre, d.estado, d.imagen, g.id as id_gerencia, g.nombre as nombre_gerencia FROM tab_direccion_dep as d, tab_gerencia_dep as g where d.id_gerencia=g.id AND d.id=?', [$id]);
                foreach($direccion as $data){
                    $array_resultado[] = array('tipo'=> 'direccion', 'id'=> $data->id, 'nombre' => $data->nombre, 'id_dependencia'=> $data->id_gerencia, 
                        'imagen'=> $data->imagen, 'nombre_dependencia'=> $data->nombre_gerencia);
                }
                return response()->json($array_resultado);
            }else if($tipo=='coordinacion'){
                $array_resultado= array();
                $count= $this->exist_gerencia_coordinacion($id);
                if($count==true){
                    $coordinacion= DB::connection('mysql')->select('SELECT c.id, c.nombre, c.estado, g.id as id_gerencia, g.nombre as nombre_gerencia FROM tab_coordinacion_dep as c, tab_gerencia_dep as g where c.id_gerencia=g.id AND c.id=?', [$id]);
                    foreach($coordinacion as $data){
                        $array_resultado[] = array('tipo'=> 'coordinacion', 'id'=> $data->id, 'nombre' => $data->nombre, 'id_dependencia'=> $data->id_gerencia, 'nombre_dependencia'=> $data->nombre_gerencia, 'tipo_dependencia'=> 'ger_'.$data->id_gerencia);
                    }
                    return response()->json($array_resultado);
                }else{
                    $coordinacion= DB::connection('mysql')->select('SELECT c.id, c.nombre, c.estado, d.id as id_direccion, d.nombre as nombre_direccion FROM tab_coordinacion_dep as c, tab_direccion_dep as d where c.id_direccion=d.id AND c.id=?', [$id]);
                    foreach($coordinacion as $data){
                        $array_resultado[] = array('tipo'=> 'coordinacion', 'id'=> $data->id, 'nombre' => $data->nombre, 'id_dependencia'=> $data->id_direccion, 'nombre_dependencia'=> $data->nombre_direccion, 'tipo_dependencia'=> 'dir_'.$data->id_direccion);
                    }
                    return response()->json($array_resultado);
                }
            }
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE REGISTRA EL NUEVO DEPARTAMENTO CREADO
    public function store_departamento(Request $r){
        $tipo= $r->tipo;
        $nombre= $r->nombre;
        $isImagen= $r->imagen;
        $date= now();

        if($tipo=='gerencia' && $isImagen=='si'){
            $totalC=  $this->exist_registro($tipo);
            if($totalC==0){
                if ($r->hasFile('file')) {
                    $files  = $r->file('file'); //obtengo el archivo
                    foreach($files as $file){
                        $filename= $file->getClientOriginalName();
                        $fileextension= $file->getClientOriginalExtension();

                        if($fileextension== $this->validarImg($fileextension)){
                            $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                            if($storeimg){
                                $sql_insert = DB::connection('mysql')->insert('insert into tab_gerencia_dep (
                                    nombre,
                                    imagen,
                                    created_at
                                ) values (?,?,?)', [$nombre, $filename, $date]);
        
                                if($sql_insert){
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
            }else{
                return response()->json(['resultado'=> 'existe']);
            }
        }else if($tipo=='direccion' && $isImagen=='si'){
            if ($r->hasFile('file')) {
                $files  = $r->file('file'); //obtengo el archivo
                foreach($files as $file){
                    $filename= $file->getClientOriginalName();
                    $fileextension= $file->getClientOriginalExtension();

                    if($fileextension== $this->validarImg($fileextension)){
                        $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                        if($storeimg){
                            $idgerencia= $r->iddependencia;
                            //$totalC=  $this->exist_registro($tipo);
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_direccion_dep (
                                nombre,
                                id_gerencia,
                                imagen,
                                created_at
                            ) values (?,?,?,?)', [$nombre, $idgerencia, $filename, $date]);

                            if($sql_insert){
                                return response()->json(['resultado'=> true]);
                            }else{
                                return response()->json(['resultado'=> false]);
                            }
                        }
                    }else{
                        return response()->json(['resultado'=> 'noimagen']);
                    }
                }
            }else{
                return response()->json(['resultado'=> 'noimagen']);
            }
        }else if($tipo=='coordinacion' && $isImagen=='no'){
            $iddep= $r->id_dep;
            $tipo_dep= $r->tipo_dep;
            //$totalC=  $this->exist_registro($tipo);
            if($tipo_dep=='gerencia'){
                $sql_insert = DB::connection('mysql')->insert('insert into tab_coordinacion_dep (
                    nombre,
                    id_gerencia,
                    created_at
                ) values (?,?,?)', [$nombre, $iddep, $date]);
            }else if($tipo_dep=='direccion'){
                $sql_insert = DB::connection('mysql')->insert('insert into tab_coordinacion_dep (
                    nombre,
                    id_direccion,
                    created_at
                ) values (?,?,?)', [$nombre, $iddep, $date]);
            }
            
            if($sql_insert){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }

        //return response()->json(['tipo'=> $tipo, 'nombre'=> $nombre, 'total'=> $this->exist_registro($tipo)]);
    }

    //FUNCION QUE VALIDA SI YA SE REGISTRO UN REGISTRO PREVIAMENTE EN LA BD
    private function exist_registro($tabla){
        $table= 'tab_'.$tabla.'_dep';
        $resultado = DB::table($table)->where('estado','1')->get();
        $wordCount = $resultado->count();

        return $wordCount;
    }

    //FUNCION QUE VALIDA SI EXISTE EL REGISTRO EN LA TABLA INFO EACH DEPARTAMENTO
    private function exist_registro_in_table($datoI, $id){
        $table= 'tab_info_each_departamento';
        $resultado= DB::connection('mysql')->select('SELECT id FROM '.$table.' WHERE id_'.$datoI.'=?', [$id]);
        $dato='';
        foreach($resultado as $data){
            $dato= $data->id;
        }

        if($dato==''){
            return false;
        }else{
            return true;
        }
    }

    //FUNCION QUE VALIDA SI EXISTE REGISTRO DE GERENCIA EN LA TABLA TAB COORDINACION DEP
    private function exist_gerencia_coordinacion($id){
        $resultado= DB::connection('mysql')->select('SELECT id_gerencia FROM tab_coordinacion_dep WHERE id=?', [$id]);
        $dato='';
        foreach($resultado as $data){
            $dato= $data->id_gerencia;
        }

        if($dato==''){
            return false;
        }else{
            return true;
        }
    }

    //FUNCION QUE VALIDA SI EXISTE REGISTRO DE DIRECCION EN LA TABLA TAB COORDINACION DEP
    private function exist_direccion_coordinacion($id){
        $resultado= DB::connection('mysql')->select('SELECT id_direccion FROM tab_coordinacion_dep WHERE id=?', [$id]);
        $dato='';
        foreach($resultado as $data){
            $dato= $data->id_direccion;
        }

        if($dato==''){
            return false;
        }else{
            return true;
        }
    }

    //FUNCION QUE INACTIVA EL DEPARTAMENTO EN LA BD SEGÚN LA ELECCIÓN
    public function inactivar_departamento(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $tipo= $request->input('tipo');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        if($tipo=='gerencia'){
            $getstate= $this->getstatusInfoDepart('gerencia',$id);
            if($getstate=='0'){
                $sql_update= DB::table('tab_gerencia_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
            }else {
                if($estado=='1'){
                    $sql_update= DB::table('tab_gerencia_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
                }else if($estado=='0'){
                    return response()->json(['resultado'=> 'enuso']);
                }
            }
        }else if($tipo=='direccion'){
            $getstate= $this->getstatusInfoDepart('direccion',$id);
            if($getstate=='0'){
                $sql_update= DB::table('tab_direccion_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
            }else {
                if($estado=='1'){
                    $sql_update= DB::table('tab_direccion_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
                }else if($estado=='0'){
                    return response()->json(['resultado'=> 'enuso']);
                }
            }
        }else if($tipo=='coordinacion'){
            $getstate= $this->getstatusInfoDepart('coordinacion',$id);
            //return $getstate;
            if($getstate=='0'){
                $sql_update= DB::table('tab_coordinacion_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
            }else {
                if($estado=='1'){
                    $sql_update= DB::table('tab_coordinacion_dep')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);
                }else if($estado=='0'){
                    return response()->json(['resultado'=> 'enuso']);
                }
            }
        }

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    private function getstatusInfoDepart($tipo,$id){
        $sql = DB::connection('mysql')->table('tab_info_each_departamento')
        ->select('estado')
        ->where('id_'.$tipo, $id)
        ->get();

        $resultado='';
        foreach($sql as $s){
            $resultado= $s->estado;
        }

        return $resultado;
    }

    //FUNCION QUE VALIDA SI ES UNA IMAGEN
    private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return $extension;
        }else{
            return "";
        }
    }

    //FUNCION QUE ACTUALIZA LA INFORMACIÓN DEL DEPARTAMENTO EN LA BD SEGÚN LA ELECCIÓN
    public function update_departamento(Request $r){
        $tipo= $r->tipo;
        $nombre= $r->nombre;
        $id= $r->id;
        $opcion= $r->isImage;
        $date= now();

        if($tipo=='gerencia'){
            if($opcion=="false"){
                if ($r->hasFile('fileedit')) {
                    $files  = $r->file('fileedit'); //obtengo el archivo
                    foreach($files as $file){
                        $filename= $file->getClientOriginalName();
                        $fileextension= $file->getClientOriginalExtension();
                        if($fileextension== $this->validarImg($fileextension)){
                            $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                            if($storeimg){
                                $sql_update= DB::table('tab_gerencia_dep')
                                    ->where('id', $id)
                                    ->update(['nombre' => $nombre, 'imagen'=> $filename, 'updated_at'=> $date]);

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
            }else if($opcion=="true"){
                $sql_update= DB::table('tab_gerencia_dep')
                    ->where('id', $id)
                    ->update(['nombre' => $nombre, 'updated_at'=> $date]);

                if($sql_update){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }
        }else if($tipo=='direccion'){
            $iddependencia= $r->iddependencia;
            if($opcion=="false"){
                if ($r->hasFile('fileedit')) {
                    $files  = $r->file('fileedit'); //obtengo el archivo
                    foreach($files as $file){
                        $filename= $file->getClientOriginalName();
                        $fileextension= $file->getClientOriginalExtension();
                        if($fileextension== $this->validarImg($fileextension)){
                            $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                            if($storeimg){
                                $sql_update= DB::table('tab_direccion_dep')
                                    ->where('id', $id)
                                    ->update(['nombre' => $nombre, 'id_gerencia'=> $iddependencia, 'imagen'=> $filename, 'updated_at'=> $date]);
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
            }else if($opcion=="true"){
                $sql_update= DB::table('tab_direccion_dep')
                    ->where('id', $id)
                    ->update(['nombre' => $nombre, 'id_gerencia'=> $iddependencia, 'updated_at'=> $date]);

                if($sql_update){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }
        }else if($tipo=='coordinacion'){
            $iddependencia= $r->iddependencia;
            $tipo_dep= $r->tipo_dep;
            if($tipo_dep=='gerencia'){
                $sql_update= DB::table('tab_coordinacion_dep')
                ->where('id', $id)
                ->update(['nombre' => $nombre, 'id_gerencia'=> $iddependencia, 'id_direccion'=> null,'updated_at'=> $date]);
            }else if($tipo_dep=='direccion'){
                $sql_update= DB::table('tab_coordinacion_dep')
                ->where('id', $id)
                ->update(['nombre' => $nombre, 'id_gerencia'=> null, 'id_direccion'=> $iddependencia, 'updated_at'=> $date]);
            }

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }

    //FUNCION QUE RETORNA LA INFORMACION DEL ITEM SELECCIONADO DE LA TABLA INFO EACH DEPARTAMENTO
    public function get_up_info_departamento($id){
        $departamento= array();
        $id_dep='';
        $resultado= DB::connection('mysql')->select('SELECT * FROM tab_info_each_departamento WHERE id=?', [$id]);
        foreach ($resultado as $r){
            $tipo= $r->tipo_dep;
            if($tipo=='gerencia'){
                $id_dep= $r->id_gerencia;
                $departamento= DB::table('tab_gerencia_dep')->where('estado','1')->get();
            }else if($tipo=='direccion'){
                $id_dep=$r->id_direccion;
                $departamento= DB::table('tab_direccion_dep')->where('estado','1')->get();
            }else if($tipo=='coordinacion'){
                $id_dep=$r->id_coordinacion;
                $departamento= DB::table('tab_coordinacion_dep')->where('estado','1')->get();
            }
            $categoria= [
                [
                    "value"=> "gerencia",
                    "dato"=> "Gerencia"
                ],
                [
                    "value"=> "direccion",
                    "dato"=> "Dirección"
                ],
                [
                    "value"=> "coordinacion",
                    "dato"=> "Coordinación"
                ]
            ];
        }
        return response()->view('Administrador.Infor.actualizar_info_depar', ['resultado' => $resultado, 'departamento'=> $departamento, 'id_dep'=>$id_dep, 'categoria'=>$categoria]);
    }

    //FUNCION QUE REGISTRA INFORMACION EN LA TABLA INFO EACH DEPARTAMENTO
    public function insert_info_departamento(Request $r){
        $tipo= $r->tipo;
        $iddepartamento= $r->iddepartamento;
        $nombre= $r->nombre;
        $email= $r->email;
        $telefono= $r->telefono;
        $extension= $r->extension;
        $date= now();

        if($tipo=='gerencia'){
            $totalC=  $this->exist_registro_in_table('gerencia', $iddepartamento);
            if($totalC==0){
                $sql_insert = DB::connection('mysql')->insert('insert into tab_info_each_departamento (
                    responsable,
                    email,
                    telefono,
                    extension,
                    tipo_dep,
                    id_gerencia,
                    id_direccion,
                    id_coordinacion,
                    created_at
                ) values (?,?,?,?,?,?,?,?,?)', [$nombre, $email, $telefono, $extension, $tipo, $iddepartamento, null, null, $date]);

                if($sql_insert){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }else{
                return response()->json(['resultado'=> 'existe']);
            }
        }else if($tipo=='direccion'){
            $totalC=  $this->exist_registro_in_table('direccion', $iddepartamento);
            if($totalC==0){
                $sql_insert = DB::connection('mysql')->insert('insert into tab_info_each_departamento (
                    responsable,
                    email,
                    telefono,
                    extension,
                    tipo_dep,
                    id_gerencia,
                    id_direccion,
                    id_coordinacion,
                    created_at
                ) values (?,?,?,?,?,?,?,?,?)', [$nombre, $email, $telefono, $extension, $tipo, null, $iddepartamento, null, $date]);

                if($sql_insert){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }else{
                return response()->json(['resultado'=> 'existe']);
            }
        }else if($tipo=='coordinacion'){
            $totalC=  $this->exist_registro_in_table('coordinacion', $iddepartamento);
            if($totalC==0){
                $sql_insert = DB::connection('mysql')->insert('insert into tab_info_each_departamento (
                    responsable,
                    email,
                    telefono,
                    extension,
                    tipo_dep,
                    id_gerencia,
                    id_direccion,
                    id_coordinacion,
                    created_at
                ) values (?,?,?,?,?,?,?,?,?)', [$nombre, $email, $telefono, $extension, $tipo, null, null, $iddepartamento, $date]);

                if($sql_insert){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }else{
                return response()->json(['resultado'=> 'existe']);
            }
        }
    }

    //FUNCION QUE ACTUALIZA INFORMACION EN LA TABLA INFO EACH DEPARTAMENTO
    public function update_info_departamento(Request $r){
        $id= $r->id;
        $responsable= $r->nombre;
        $email= $r->email;
        $telefono= $r->telefono;
        $extension= $r->extension;
        $date= now();

        $sql_update= DB::table('tab_info_each_departamento')
            ->where('id', $id)
            ->update(['responsable' => $responsable, 'email'=> $email, 'telefono'=> $telefono, 'extension'=> $extension, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA INFO EACH DEPARTAMENTO
    public function inactivar_info_departamento(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_info_each_departamento')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
