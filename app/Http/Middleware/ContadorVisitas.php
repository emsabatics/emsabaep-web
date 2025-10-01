<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContadorVisitas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica que la ruta actual sea la principal (/)
        if ($request->is('/')) {
            $hoy = now()->toDateString(); 

            // Verificar si ya existe un registro para hoy
            $registro = DB::table('tab_visitas')
                ->where('pagina', 'inicio')
                ->where('fecha', $hoy)
                ->first();

            if ($registro) {
                // Si existe, incrementamos
                DB::table('tab_visitas')
                    ->where('id', $registro->id)
                    ->update([
                        'contador' => DB::raw('contador + 1'),
                        'updated_at' => now()
                    ]);
            } else {
                // Si no existe, creamos registro nuevo
                DB::table('tab_visitas')->insert([
                    'pagina' => 'inicio',
                    'fecha' => $hoy,
                    'contador' => 1,
                    'updated_at' => now()
                ]);
            }
        }

        return $next($request);
    }
}
