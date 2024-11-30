<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TecnicoController;

class Login_tecnicoController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'celularTecnico' => 'required|string',
            'password' => 'required|string',
        ]);

        $celularTecnico = $request->input('celularTecnico');
        $password = $request->input('password');

        // Buscar el técnico por celularTecnico en la tabla Tecnicos
        $tecnico = DB::table('Tecnicos')
            ->where('celularTecnico', $celularTecnico)
            ->first();

        // Verificar si se encontró el técnico y luego validar la contraseña
        if ($tecnico) {
            $loginTecnico = DB::table('login_tecnicos')
                ->where('idTecnico', $tecnico->idTecnico) // Usar idTecnico del técnico encontrado
                ->first();

            if ($loginTecnico && Hash::check($password, $loginTecnico->password)) {
                // Verificar si es el primer inicio de sesión
                $isFirstLogin = $loginTecnico->isFirstLogin;

                // Si es el primer inicio de sesión, actualizarlo a 1
                if ($isFirstLogin == 0) {
                    DB::table('login_tecnicos')
                        ->where('idTecnico', $tecnico->idTecnico)
                        ->update(['isFirstLogin' => 1]);
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login exitoso',
                    'idTecnico' => $tecnico->idTecnico,
                    'isFirstLogin' => $isFirstLogin == 0, 
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Credenciales inválidas'
        ], 401);
    }

    public function getAllTecnicos()
    {
        $tecnicos = DB::table('Tecnicos')
            ->select('celularTecnico')
            ->get();

        return response()->json($tecnicos);
    }

    public function getCsrfToken()
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }

    public function getVentasIntermediadas($idTecnico)
    {
        try {
            $ventas = DB::table('VentasIntermediadas')
                ->join('EstadoVentas', 'VentasIntermediadas.idEstadoVenta', '=', 'EstadoVentas.idEstadoVenta')
                ->where('VentasIntermediadas.idTecnico', $idTecnico)
                ->select(
                    'VentasIntermediadas.*',
                    'EstadoVentas.nombre_EstadoVenta as estado_nombre'
                )
                ->get();
            
            // Si no se encuentran resultados
            if ($ventas->isEmpty()) {
                return response()->json(['message' => 'No se encontraron ventas para el técnico.'], 404);
            }

            return response()->json($ventas);
        } catch (\Exception $e) {
            // Capturar el error y devolver el mensaje de error
            return response()->json(['error' => 'Hubo un problema al procesar la solicitud.', 'message' => $e->getMessage()], 500);
            }
    }

    public function obtenerTecnicoPorId($idTecnico)
    {
        $datostecnico = DB::table('Tecnicos')
            ->where('idTecnico', $idTecnico)
            ->first();

        // Verificar si se encontraron los datos del técnico
        if (!$datostecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        // Obtener los oficios asociados al técnico
        $oficios = DB::table('Oficios')
            ->join('TecnicosOficios', 'Oficios.idOficio', '=', 'TecnicosOficios.idOficio')
            ->where('TecnicosOficios.idTecnico', $idTecnico)
            ->select('Oficios.idOficio', 'Oficios.nombre_Oficio')
            ->get();

        return response()->json([
            'tecnico' => [
                'idTecnico' => $datostecnico->idTecnico,
                'nombreTecnico' => $datostecnico->nombreTecnico,
                'celularTecnico' => $datostecnico->celularTecnico,
                'fechaNacimiento_Tecnico' => $datostecnico->fechaNacimiento_Tecnico,
                'totalPuntosActuales_Tecnico' => $datostecnico->totalPuntosActuales_Tecnico,
                'historicoPuntos_Tecnico' => $datostecnico->historicoPuntos_Tecnico,
                'rangoTecnico' => $datostecnico->rangoTecnico,
                'oficios' => $oficios, // Añadir los oficios como lista
            ]
        ]);
    }


    public function getAllLoginTecnicos() 
    {
        $tecnicos = DB::table('login_tecnicos')->get(); 
        return response()->json($tecnicos);
    }

    public function obtenerRecompensas()
    {
        // Obtener todas las recompensas con su tipo de recompensa desde la tabla 'Recompensas'
        $recompensas = DB::table('Recompensas')
            ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
            ->select(
                'Recompensas.idRecompensa',
                'TiposRecompensas.nombre_TipoRecompensa as tipoRecompensa', // Traemos el nombre del tipo de recompensa
                'Recompensas.descripcionRecompensa',
                'Recompensas.costoPuntos_Recompensa',
                'Recompensas.stock_Recompensa' // Agregar el stock si es necesario
            )
            ->get();

        return response()->json($recompensas);
    }


    public function changePassword(Request $request)
    {
        $request->validate([
            'idTecnico' => 'required|string',
            'currentPassword' => 'required|string',
            'newPassword' => 'required|string|min:2',
        ]);

        $tecnico = DB::table('login_tecnicos')
            ->where('idTecnico', $request->input('idTecnico'))
            ->first();

        if ($tecnico && Hash::check($request->input('currentPassword'), $tecnico->password)) {
            DB::table('login_tecnicos')
                ->where('idTecnico', $request->input('idTecnico'))
                ->update(['password' => Hash::make($request->input('newPassword'))]);

            return response()->json(['status' => 'success', 'message' => 'Contraseña cambiada con éxito']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'La contraseña actual no es correcta'], 401);
        }
    }

    public function changeJobs(Request $request)
    {
        $request->validate([
            'idTecnico' => 'required|string',
            'oficios' => 'required|array',
            'oficios.*' => 'exists:Oficios,idOficio',
            'password' => 'required|string|min:3',
        ]);
    
        // Obtener el técnico desde la tabla login_tecnicos para verificar la contraseña
        $loginTecnico = DB::table('login_tecnicos')
            ->where('idTecnico', $request->input('idTecnico'))
            ->first();
    
        if (!$loginTecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }
    
        // Verificar la contraseña
        if (!Hash::check($request->password, $loginTecnico->password)) {
            return response()->json(['message' => 'Contraseña incorrecta'], 400);
        }
    
        // Eliminar los oficios antiguos y agregar los nuevos en la tabla TecnicosOficios
        DB::table('TecnicosOficios')
            ->where('idTecnico', $request->input('idTecnico'))
            ->delete();
    
        foreach ($request->oficios as $oficioId) {
            DB::table('TecnicosOficios')->insert([
                'idTecnico' => $request->input('idTecnico'),
                'idOficio' => $oficioId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    
        return response()->json(['message' => 'Oficios actualizados correctamente']);
    }

    public function getAvailableJobs()
    {
        // Obtener todas las recompensas desde la tabla 'Recompensas'
        $recompensas = DB::table('oficios')
            ->select('idOficio', 'nombre_Oficio')
            ->get();

        return response()->json($recompensas);
    }

    public function getAllFullModelsTecnicos() 
    {
        $tecnicos = TecnicoController::returnModelsTecnicosWithOficios();
        return $tecnicos;
    }
}