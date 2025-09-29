<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Session::get('usuario')){
            return response()->view('Administrador.home');

            //$userId = $this->getIdUser();

            /*$permisos = DB::table('tab_permisos as p')
                ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
                ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
                ->where('p.idusuario', '=',$userId)
                ->where('m.estado_vis_novis','=', '1')
                ->orWhere('s.estado_vis_novis','=','1')
                ->select(
                    'm.id as idmodulo',
                    'm.nombre as modulo',
                    'm.estado_vis_novis as mod_visible',
                    'm.icono',
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
            /*
            //VALE
            $userId = $this->getIdUser();
            
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
            WHERE p.idusuario = ? ORDER BY m.nivel_prioridad ASC', [$userId]);
            
            // 2. Cargar JSON de rutas
            $menuJson = json_decode(Storage::get('menu_config.json'), true);

            // 3. Vincular rutas a permisos
            foreach ($permisos as $permiso) {
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
            }*/

            /*$permisos = collect($permisos);

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
            });*/

            /*
            //VALE
            $permisosJS = array_map(function ($item) {
                return [
                    'modulo' => $item->modulo,
                    'submodulo' => $item->submodulo,
                    'guardar' => $item->guardar,
                    'actualizar' => $item->actualizar,
                    'eliminar' => $item->eliminar,
                    'descargar' => $item->descargar,
                    'configurar' => $item->configurar
                ];
            }, $permisos);

            return $permisos;*/
        }else{
            //return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
            return response()->view('Errores.403', [], 403);
        }
        //return response()->view('Administrador.home');
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
