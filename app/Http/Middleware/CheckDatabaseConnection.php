<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckDatabaseConnection
{
    public function handle(Request $request, Closure $next)
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            // Aquí puedes registrar en logs si quieres
            \Log::error("Error de conexión a la BD: " . $e->getMessage());

            // Muestra una vista amigable
            return response()->view('Errores.500', [], 500);
        }

        return $next($request);
    }
}
