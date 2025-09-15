<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Services\PermisosService;

class CheckRutaPermiso
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
        /*$permisos = session('permisos', collect());
        $ruta = $request->route()->getName(); // lee el name de la ruta

        $tienePermiso = $permisos->first(function ($permiso) use ($ruta) {
            return $permiso->submodulo === $ruta || $permiso->modulo === $ruta;
        });

        if (!$tienePermiso) {
            abort(403, 'Acceso denegado.');
        }*/

        $userId = $this->getIdUser();
        /*
        // Leemos permisos desde cache
        $permisos = Cache::get("permisos_user_{$userId}", collect());

        if(!$permisos){
            $permisos = app(PermisosService::class)->generarPermisos($userId);
            Cache::put("permisos_user_{$userId}", $permisos, now()->addMinutes(30));
        }*/

        $permisos = app(PermisosService::class)->generarPermisos($userId);

        // Nombre de la ruta (route name)
        $ruta = $request->route()->getName();

        $permisos = collect($permisos); // convierte el array a Collection

        // Validar si el usuario tiene permiso
        $tienePermiso = $permisos->first(function ($permiso) use ($ruta) {
            return $permiso->ruta_submodulo === $ruta || $permiso->ruta_modulo === $ruta;
        });

        /*$tienePermiso = null;
        foreach ($permisos as $permiso) {
            if ($permiso['ruta_submodulo'] === $ruta || $permiso['ruta_modulo'] === $ruta) {
                $tienePermiso = $permiso;
                break;
            }
        }*/

        if (!$tienePermiso) {
            //abort(403, 'Acceso denegado.');
            return response()->view('Errores.403', [], 403);
        }

        return $next($request);
    }

    private function getIdUser(){
        $user = Session::get('usuario');

        $sql = DB::connection('mysql')->table('users')->select('id')->where('user','=', $user)->get();

        $iduser = 0;

        foreach($sql as $s){
            $iduser = $s->id;
        }

        return $iduser;
    }
}
