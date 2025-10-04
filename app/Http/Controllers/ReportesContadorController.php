<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesContadorController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            // Si el usuario envía rango de fechas desde el selector
            $fechaInicio = now()->subDays(10)->toDateString();
            $fechaFin    = now()->toDateString();

            $visitas = DB::connection('mysql')->table('tab_visitas')
                ->select('fecha', DB::raw('SUM(contador) as total'))
                ->where('pagina', 'inicio')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->orderBy('fecha', 'asc')
                ->get();

            $contador = DB::connection('mysql')->table('tab_visitas')
                ->selectRaw('SUM(contador) as total')
                ->first();
            
            // Acceder al valor:
            $totalValor = $contador->total;

            // Preparamos arrays para Chart.js
            $labels = $visitas->pluck('fecha');
            $dataTotal   = $visitas->pluck('total');

            return view('Administrador.reportesContador.reportescontador', compact('labels', 'dataTotal', 'fechaInicio', 'fechaFin', 'totalValor'));
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function filtrar(Request $r)
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            // Si el usuario envía rango de fechas desde el selector
            $fechaInicio = $r->fecha_inicio;
            $fechaFin    = $r->fecha_fin;

            $visitas = DB::connection('mysql')->table('tab_visitas')
                ->select('fecha', DB::raw('SUM(contador) as total'))
                ->where('pagina', 'inicio')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->orderBy('fecha', 'asc')
                ->get();

            $contador = DB::connection('mysql')->table('tab_visitas')
                ->selectRaw('SUM(contador) as total')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->first();
            
            // Acceder al valor:
            $totalValor = $contador->total;

            // Preparamos arrays para Chart.js
            $labels = $visitas->pluck('fecha');
            $dataTotal   = $visitas->pluck('total');

            return response()->json(['labels'=> $labels, 'data' => $dataTotal, 'totalValor'=> $totalValor]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
