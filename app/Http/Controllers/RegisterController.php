<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class RegisterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*if(Session::get('usuario')){
            return redirect()->route('/home');
        }else{
            return response()->view('Administrador.registro');
        }*/
        return response()->view('Administrador.registro');
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

    public function registrar_usuario(Request $request){
        $user = $request->input('datoUsuario');
        $sql_getuser= DB::connection('mysql')->select('select user from users where user= ?', [$user]);

        $usuario='';

        foreach($sql_getuser as $r){
            $usuario= $r->user;
        }

        $nameUsuario = $request->nameUsuario;

        //return response()->json(['nameusuario'=> $nameUsuario, 'usuario'=> $user]);

        if($usuario==''){
            $getusuario= $request->input('datoUsuario');
            $nameUsuario = $request->input('nameUsuario');
            $clave = $request->input('passwordUsuario');
            $tipousuario= "Administrador";
            $datetime= now();

            $clave= Hash::make($clave);

            $sql_insert = DB::connection('mysql')->insert('insert into users (
                user,
                password,
                nombre_usuario,
                tipo_usuario,
                created_at
            ) values (?,?,?,?,?)', [$getusuario, $clave, $nameUsuario, $tipousuario, $datetime]);

            if($sql_insert){
                return response()->json(['registro'=> true]);
            }else{
                return response()->json(['registro'=> false]);
            }
        }else{
            return response()->json(['usuario'=> false]);
        }
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
