<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Exports\SolicitudesExcelExport;
use Maatwebsite\Excel\Facades\Excel;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Http\Request;

class ReportesController extends Controller
{
    public function exportarSolicitudesExcel()
    {
        if(Session::get('usuario')){

            $arraydatos= array();

            $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->orderBy('fecha', 'asc')
                ->get();

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono,
                    'ultima_modificacion'=> $fecha_modificacion, 'estado_solicitud'=> $m->estado_solicitud, 'nombre_usuario'=> $nombre_usuario);
            }
            
            //return $arraydatos;
            return Excel::download(new SolicitudesExcelExport($arraydatos), 'reporte_solicitudes.xlsx');
            //return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> collect($arraydatos)]);
        }
        /*$mensajes = DB::select("SELECT cuenta, nombres, contactos, fecha_ingreso, estado, ultima_modificacion FROM mensajes");
        $datos = array_map(fn($item) => (array) $item, $mensajes);

        return Excel::download(new MensajesExport($datos), 'reporte_mensajes.xlsx');*/
    }

    public function exportarFilterSolicitudesExcel(Request $request)
    {
        if(Session::get('usuario')){

            $estado = $request->estado;
            $inicio = $request->fecha_inicio;
            $hasta = $request->fecha_fin;

            $nestado='';
            if($estado=='tram'){
                $nestado= 'En Trámite';
            }else if($estado=='end'){
                $nestado= 'Finalizado';
            }

            $arraydatos= array();

            if($estado=='all'){
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }else{
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->where('estado_solicitud','=', $nestado)
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }
            

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono,
                    'ultima_modificacion'=> $fecha_modificacion, 'estado_solicitud'=> $m->estado_solicitud, 'nombre_usuario'=> $nombre_usuario);
            }
            
            //return $arraydatos;
            return Excel::download(new SolicitudesExcelExport($arraydatos), 'reporte_solicitudes_filtro.xlsx');
            //return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> collect($arraydatos)]);
        }
        /*$mensajes = DB::select("SELECT cuenta, nombres, contactos, fecha_ingreso, estado, ultima_modificacion FROM mensajes");
        $datos = array_map(fn($item) => (array) $item, $mensajes);

        return Excel::download(new MensajesExport($datos), 'reporte_mensajes.xlsx');*/
    }

    public function exportarSolicitudesPDF()
    {
        if(Session::get('usuario')){

            $arraydatos= array();

            $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->orderBy('fecha', 'asc')
                ->get();

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono,
                    'ultima_modificacion'=> $fecha_modificacion, 'estado_solicitud'=> $m->estado_solicitud, 'nombre_usuario'=> $nombre_usuario);
            }
            
            $mensajes = collect($arraydatos);
            //return $arraydatos;
            //$pdf = Pdf::loadView('Administrador.Reportes.solicitudespdf', $arraydatos);
            $pdf = Pdf::loadView('Administrador.Reportes.solicitudespdf', compact('mensajes'))->setPaper('A4', 'landscape');
            return $pdf->download('reporte_solicitudes.pdf');
            //return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> collect($arraydatos)]);
        }

        /*$pdf = Pdf::loadView('pdf.reporte', $datos);

        return $pdf->download('reporte.pdf');*/
    }

    public function exportarFilterSolicitudesPDF(Request $request)
    {
        if(Session::get('usuario')){

            $estado = $request->estado;
            $inicio = $request->fecha_inicio;
            $hasta = $request->fecha_fin;

            $nestado='';
            if($estado=='tram'){
                $nestado= 'En Trámite';
            }else if($estado=='end'){
                $nestado= 'Finalizado';
            }

            $arraydatos= array();

            if($estado=='all'){
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }else{
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->where('estado_solicitud','=', $nestado)
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }
            

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono,
                    'ultima_modificacion'=> $fecha_modificacion, 'estado_solicitud'=> $m->estado_solicitud, 'nombre_usuario'=> $nombre_usuario);
            }
            
            $mensajes = collect($arraydatos);
            //return $arraydatos;
            //$pdf = Pdf::loadView('Administrador.Reportes.solicitudespdf', $arraydatos);
            $pdf = Pdf::loadView('Administrador.Reportes.solicitudespdf', compact('mensajes'))->setPaper('A4', 'landscape');
            return $pdf->download('reporte_filtro_solicitudes.pdf');
            //return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> collect($arraydatos)]);
        }

        /*$pdf = Pdf::loadView('pdf.reporte', $datos);

        return $pdf->download('reporte.pdf');*/
    }
}
