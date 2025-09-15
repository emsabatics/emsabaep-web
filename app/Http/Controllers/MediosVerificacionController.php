<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MediosVerificacionController extends Controller
{
    //INDEX PÁGINA PRINCIPAL MEDIOS DE VERIFICACION
    public function index()
    {
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $dato = array();
            $mediosv= DB::connection('mysql')->table('tab_mediosv')
            ->join('tab_anio', 'tab_mediosv.id_anio','=','tab_anio.id')
            ->select('tab_mediosv.*', 'tab_anio.nombre as anio')
            ->get();
            foreach ($mediosv as $k) {
                $idmediosv= $k->id;
                $numfiles= $this->getNumFiles($idmediosv);
                $dato[]=array('id'=> $idmediosv, 'id_anio'=>$k->id_anio, 'titulo'=>$k->titulo, 'estado'=> $k->estado, 'num_files'=> $numfiles);
            }
            return response()->view('Administrador.Documentos.mediosv.mediosv', ['mediosv'=> json_encode($dato)]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    private function getNumFiles($idn){
        $estado="1";
        $resultado= 0;

        $sql= DB::connection('mysql')->select('SELECT COUNT(*) as total FROM tab_archivos_mediosv WHERE id_archivo=? AND estado=?',[$idn,$estado]); 

        foreach($sql as $r){
            $resultado= $r->total;
        }

        return $resultado;
    }

    private function getIdArchivosMediosV($idn){
        $estado="1";
        $resultado= 0;

        $sql= DB::connection('mysql')->select('SELECT id_archivo FROM tab_archivos_mediosv WHERE id=?',[$idn]); 

        foreach($sql as $r){
            $resultado= $r->id_archivo;
        }

        return $resultado;
    }

    //FUNCION QUE DESPLIEGA LA INTERFAZ DE REGISTRAR MEDIOS DE VERIFICACION
    public function mediosv_register(){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            return view('Administrador.Documentos.mediosv.registrar_mediosv', ['anio'=> $anio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REGISTRA EL MEDIO DE VERIFICACION
    public function registro_mediosv(Request $r){
        if ($r->hasFile('file') ) {
            $anio= $r->anio;
            $titulo= $r->inputMTitle;
            $objeto= $r->objeto;
            $date= now();

            if($anio==$this->getTabMediosV($anio)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                $LAST_ID=0;
                $sql_insert= DB::connection('mysql')->table('tab_mediosv')->insertGetId(
                    ['id_anio'=> $anio, 'titulo'=> $titulo, 'created_at'=> $date]
                );

                $LAST_ID= $sql_insert;

                if($sql_insert){
                    $filesmediosv  = $r->file('file'); //obtengo el archivo MEDIOS VERIFICACION
                    //$res= $objeto->getContent();
                    $array = json_decode($objeto, true);
                    $longcadena= sizeof($array);
                    $date= now();
                    $j=0;

                    for($i=0; $i<$longcadena; $i++){
                        $titulofile= $array[$i]['value'];
                        $aliasfilemv= $array[$i]['alias'];

                        $contentfilemediosv= $filesmediosv[$i];
                        $filenamemediosv= $filesmediosv[$i]->getClientOriginalName();
                        $fileextensionmediosv= $filesmediosv[$i]->getClientOriginalExtension();

                        $newnamemediosv= $aliasfilemv.".".$fileextensionmediosv;

                        if($fileextensionmediosv== $this->validarFilesMv($fileextensionmediosv)){
                            $storemediosv= Storage::disk('doc_medios_v')->put($newnamemediosv,  \File::get($contentfilemediosv));
                            if($storemediosv){
                                $sql_insert_file_mv = DB::connection('mysql')->insert('insert into tab_archivos_mediosv (
                                    id_archivo, titulo, archivo, created_at
                                ) values (?,?,?,?)', [$LAST_ID, $titulofile, $newnamemediosv, $date]);
                
                                if($sql_insert_file_mv){
                                    $j++;
                                }
                            }else{
                                return response()->json(["resultado"=> false]);
                            }
                        }else{
                            return response()->json(['resultado'=> 'nofile']);
                        }
                    }

                    if($longcadena==$j){
                        return response()->json(["resultado"=> true]);
                    }else{
                        DB::table('tab_mediosv')->where('id', '=', $LAST_ID)->delete();
                        return response()->json(["resultado"=> false]);
                    }
                }else{

                    return response()->json(["resultado"=> false]);
                }
            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE VALIDA SI ES UN PDF
    private function validarFile($extension){
        $validar_extension= array("pdf");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    private function validarFilesMv($extension){
        $validar_extension= array("pdf", "mp4");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    private function getTabMediosV($year){
        $sql= DB::connection('mysql')->select('SELECT id_anio FROM tab_mediosv WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_anio;
        }

        return $resultado;
    }

    //FUNCION QUE DESPLIEGA LA INTERFAZ DE ACTUALIZAR MEDIOS VERIFICACION
    public function edit_mediosv($id){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $estado='1';
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $sql_archivos = DB::connection('mysql')->select('SELECT * FROM tab_archivos_mediosv WHERE id_archivo=? AND estado=?', [$id, '1']);
            $filemediosv = DB::table('tab_mediosv')
            ->join('tab_anio', 'tab_mediosv.id_anio', '=', 'tab_anio.id')
            ->select('tab_mediosv.*', 'tab_mediosv.id_anio', 'tab_anio.nombre as anio')
            ->where('tab_mediosv.id','=', $id)
            ->get();
            return view('Administrador.Documentos.mediosv.actualizar_mediosv', ['filemediosv'=> $filemediosv, 'archivosmv'=> $sql_archivos, 'anio'=> $anio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA MEDIOS V
    public function inactivar_mediosv(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_mediosv')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            $idmv= $this->getIdArchivosMediosV($id);
            $count= $this->getNumFiles($idmv);
            return response()->json(['resultado'=> true, 'count'=> $count]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA ARCHIVOS MEDIOS V
    public function inactivar_file_mediosv(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_archivos_mediosv')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            $idmv= $this->getIdArchivosMediosV($id);
            $count= $this->getNumFiles($idmv);
            return response()->json(['resultado'=> true, 'count'=> $count]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC LOTAIP
    public function view_mediosv($id){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $filemediosv= DB::connection('mysql')
            ->select('SELECT titulo, archivo 
                FROM tab_archivos_mediosv 
                WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.mediosv.viewmediosv', ['filemediosv'=> $filemediosv]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function update_texto_mediosv(Request $r){
        $idmv= $r->idmv;
        $titulo = $r->titulo;
        $date = now();

        $sql_update = DB::connection('mysql')->table('tab_mediosv')
        ->where('id','=', $idmv)
        ->update(['titulo'=> $titulo, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function update_mediosv(Request $r){
        if ($r->hasFile('file') ) {
            $idmv= $r->idmv;
            $objeto= $r->objeto;
            $date= now();

            $filesmediosv  = $r->file('file'); //obtengo el archivo MEDIOS VERIFICACION
            //$res= $objeto->getContent();
            $array = json_decode($objeto, true);
            $longcadena= sizeof($array);
            $date= now();
            $j=0;

            for($i=0; $i<$longcadena; $i++){
                $titulofile= $array[$i]['value'];
                $aliasfilemv= $array[$i]['alias'];

                $contentfilemediosv= $filesmediosv[$i];
                $filenamemediosv= $filesmediosv[$i]->getClientOriginalName();
                $fileextensionmediosv= $filesmediosv[$i]->getClientOriginalExtension();

                $newnamemediosv= $aliasfilemv.".".$fileextensionmediosv;

                if($fileextensionmediosv== $this->validarFilesMv($fileextensionmediosv)){
                    $storemediosv= Storage::disk('doc_medios_v')->put($newnamemediosv,  \File::get($contentfilemediosv));
                    if($storemediosv){
                        $sql_insert_file_mv = DB::connection('mysql')->insert('insert into tab_archivos_mediosv (
                            id_archivo, titulo, archivo, created_at
                        ) values (?,?,?,?)', [$idmv, $titulofile, $newnamemediosv, $date]);
                
                        if($sql_insert_file_mv){
                            $j++;
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }

            if($longcadena==$j){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }

        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR MEDIOS DE VERIFICACION
    public function download_mediosv($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_archivos_mediosv WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/medios_verificacion/'.$archivo;
        $path = storage_path('app/'.$subpath);

        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_medios_v')->exists($archivo))
        {
            return response()->download($path);
        }else{
            abort(404);
        }
        
    }
}
