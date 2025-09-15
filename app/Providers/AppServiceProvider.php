<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use App\Services\PermisosService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(PermisosService $permisosService)
    {
        //
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) use ($permisosService) {
            $userId = $this->getIdUser();

            /*$permisos = Cache::remember("permisos_user_{$userId}", 120, function() use ($userId){
                
                // 1. Consultar permisos desde BD
                $permisos = DB::connection('mysql')->select('SELECT 
                m.id AS idmodulo,
                m.nombre AS modulo,
                m.estado_vis_novis AS mod_visible,
                m.icono,
                s.id AS idsubmodulo,
                s.submodulo,
                s.estado_vis_novis AS submod_visible,
                p.guardar,
                p.actualizar,
                p.eliminar,
                p.descargar,
                p.configurar
                FROM tab_permisos p
                INNER JOIN tab_modulo m ON m.id = p.idmodulo
                LEFT JOIN tab_submodulo s ON s.id = p.idsubmodulo
                WHERE p.idusuario = ?
                AND (m.estado_vis_novis = ? OR s.estado_vis_novis = ?)', [$userId, '1', '1']);

                // 2. Cargar JSON de rutas
                $menuJson = json_decode(Storage::get('menu_config.json'), true);

                // 3. Vincular rutas a permisos
                foreach ($permisos as $permiso) {
                    // Inicializamos las propiedades para que siempre existan
                    $permiso->ruta_modulo = null;
                    $permiso->ruta_submodulo = null;

                    foreach ($menuJson as $mod) {
                        if ($mod['modulo'] === $permiso->modulo) {
                            $permiso->ruta_modulo = $mod['ruta'] ?? '#';

                            foreach ($mod['submodulos'] as $sub) {
                                if ($sub['nombre'] === $permiso->submodulo) {
                                    $permiso->ruta_submodulo = $sub['ruta'] ?? '#';
                                }
                            }
                        }
                    }
                }

                return $permisos;
            });*/

            /*$permisos = DB::connection('mysql')->select('SELECT 
                m.id AS idmodulo,
                m.nombre AS modulo,
                m.estado_vis_novis AS mod_visible,
                m.icono,
                s.id AS idsubmodulo,
                s.submodulo,
                s.estado_vis_novis AS submod_visible,
                p.guardar,
                p.actualizar,
                p.eliminar,
                p.descargar,
                p.configurar
            FROM tab_permisos p
            INNER JOIN tab_modulo m ON m.id = p.idmodulo
            LEFT JOIN tab_submodulo s ON s.id = p.idsubmodulo
            WHERE p.idusuario = ?
            AND (m.estado_vis_novis = ? OR s.estado_vis_novis = ?)', [$userId, '1', '1']);*/

            //ORIGINAL DOS
            /*$permisos = DB::table('tab_permisos as p')
            ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
            ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
            ->where('p.idusuario', $userId)
            ->where('m.estado_vis_novis','=', '1')
            ->orWhere('s.estado_vis_novis','=','1')
            ->select(
                'm.id as idmodulo',
                'm.nombre as modulo',
                'm.icono',
                'm.estado_vis_novis as mod_visible',
                's.id as idsubmodulo',
                's.submodulo',
                's.estado_vis_novis as submod_visible',
                'p.guardar',
                'p.actualizar',
                'p.eliminar',
                'p.descargar',
                'p.configurar'
            )
            ->orderBy('m.id')
            ->orderBy('s.id')
            ->get();*/

            //ORIGINAL UNO
            // 1. Obtener permisos desde BD
            // Cache del menú por usuario (10 minutos de ejemplo)
            /*$permisos = Cache::remember("menu_usuario_{$userId}", 5, function () use ($userId) {
                return DB::table('tab_permisos as p')
                ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
                ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
                ->where('p.idusuario', $userId)
                ->where('m.estado_vis_novis','=', '1')
                ->orWhere('s.estado_vis_novis','=','1')
                ->select(
                    'm.id as idmodulo',
                    'm.nombre as modulo',
                    'm.icono',
                    'm.estado_vis_novis as mod_visible',
                    's.id as idsubmodulo',
                    's.submodulo',
                    's.estado_vis_novis as submod_visible',
                    'p.guardar',
                    'p.actualizar',
                    'p.eliminar',
                    'p.descargar',
                    'p.configurar'
                )
                ->orderBy('m.id')
                ->orderBy('s.id')
                ->get();
            });*/

            /*// Leemos permisos desde cache
            $permisos = Cache::get("permisos_user_{$userId}", collect());

            if(!$permisos){
                $permisos = app(PermisosService::class)->generarPermisos($userId);
                Cache::put("permisos_user_{$userId}", $permisos, now()->addMinutes(30));
            }*/
            
            $permisos = $permisosService->generarPermisos($userId);

            $permisos = collect($permisos);

            // 4. Variables para JS — permisos agrupados por submódulo
            $permisosJS = $permisos->map(function ($item) {
                return [
                    'modulo' => $item->modulo,
                    'submodulo' => $item->submodulo,
                    'guardar' => $item->guardar,
                    'actualizar' => $item->actualizar,
                    'eliminar' => $item->eliminar,
                    'descargar' => $item->descargar,
                    'configurar' => $item->configurar
                ];
            });

            // 5. Compartir con todas las vistas
            $view->with('permisosMenu', $permisos);
            $view->with('permisosJS', $permisosJS);
        });
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
