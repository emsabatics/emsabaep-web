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
            //return response()->view('Administrador.home');

            $userId = $this->getIdUser();

            $permisos = DB::table('tab_permisos as p')
                ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
                ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
                ->where('p.idusuario', $userId)
                ->select(
                    'm.id as idmodulo',
                    'm.nombre as modulo',
                    'm.icono',
                    's.id as idsubmodulo',
                    's.submodulo',
                    'p.guardar',
                    'p.actualizar',
                    'p.eliminar',
                    'p.descargar',
                    'p.configurar'
                )
                ->orderBy('m.id')
                ->orderBy('s.id')
                ->get();

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
            }

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

            return $permisos;
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
