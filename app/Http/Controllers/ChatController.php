<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

//use App\Models\Message;
use App\Models\File;
//use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    public function index()
    {
        return view('Viewmain.Chat.index');
    }

    public function sendMessage(Request $request)
    {
        $id_sol_men = 1;
        $idusuario = 1;
        $idadmin = 1;
        $mensaje = $request->message;
        $date = now();
        // Obtener la fecha en formato "YYYY-MM-DD"
        $fechaFormateada = $date->format('Y-m-d');
        // Guarda el mensaje en la base de datos
        DB::table('tab_chat')->insert([
            'id_sol_men' => $id_sol_men,
            'idusuario' => $idusuario,
            'idadmin' => $idadmin,
            'mensaje' => $mensaje,
            'fecha' => $fechaFormateada,
            'created_at' => $date,
        ]);
    }

    public function getMessages()
    {
        // Recupera los mensajes más recientes
        $messages = DB::table('tab_chat')->orderBy('created_at', 'desc')->limit(10)->get();
        return response()->json($messages);
    }

    public function store(Request $request)
    {
        // Validar los datos
        $validator = Validator::make($request->all(), [
            'sender' => 'required|string|max:255',
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240', // 10MB máximo
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Iniciar la transacción
        \DB::beginTransaction();

        try {
            // Insertar el mensaje en la tabla messages
            $message = Message::create([
                'sender' => $request->sender,
                'message_content' => $request->message,
            ]);

            // Si hay un archivo, subirlo y asociarlo al mensaje
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $path = $file->store('uploads', 'public'); // Guarda el archivo en el directorio "storage/app/public/uploads"
                
                // Crear el registro del archivo en la tabla files
                $fileRecord = File::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getMimeType(),
                    'file_path' => $path,
                    'message_id' => $message->id,
                ]);
            }

            // Confirmar la transacción
            \DB::commit();

            return response()->json(['message' => 'Mensaje y archivo enviados correctamente'], 200);

        } catch (\Exception $e) {
            // Si algo falla, hacer rollback
            \DB::rollBack();
            return response()->json(['error' => 'Error al guardar el mensaje y archivo'], 500);
        }
    }
}
