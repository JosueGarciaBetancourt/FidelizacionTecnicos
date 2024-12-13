<?php

namespace App\Http\Controllers;

use App\Models\Recompensa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\TecnicoController;
use Illuminate\Support\Str;

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

                // Generar una API Key única
                $apiKey = Str::random(60);  // Genera una clave de 60 caracteres

                // Almacenar la API Key en la base de datos (puedes agregar una nueva columna en la tabla 'login_tecnicos' para esto)
                DB::table('login_tecnicos')
                    ->where('idTecnico', $tecnico->idTecnico)
                    ->update(['api_key' => $apiKey]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Login exitoso',
                    'idTecnico' => $tecnico->idTecnico,
                    'apiKey' => $apiKey,
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

    public function getVentasIntermediadasFiltradas($idTecnico)
    {
        try {
            // Obtener ventas intermediadas excluyendo aquellas que tienen solicitudes pendientes
            $ventas = DB::table('VentasIntermediadas')
                ->leftJoin('SolicitudesCanjes', function ($join) {
                    $join->on('VentasIntermediadas.idVentaIntermediada', '=', 'SolicitudesCanjes.idVentaIntermediada')
                        ->where('SolicitudesCanjes.idEstadoSolicitudCanje', 1); // Estado pendiente
                })
                ->join('EstadoVentas', 'VentasIntermediadas.idEstadoVenta', '=', 'EstadoVentas.idEstadoVenta')
                ->where('VentasIntermediadas.idTecnico', $idTecnico)
                ->whereNull('SolicitudesCanjes.idVentaIntermediada') // Filtrar las ventas sin solicitudes pendientes
                ->select(
                    'VentasIntermediadas.*',
                    'EstadoVentas.nombre_EstadoVenta as estado_nombre'
                )
                ->get();

            // Si no se encuentran resultados
            if ($ventas->isEmpty()) {
                return response()->json(['message' => 'No se encontraron ventas disponibles para el técnico.'], 404);
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
        // Obtener todas las recompensas activas (donde deleted_at es null) con su tipo de recompensa
        $recompensas = DB::table('Recompensas')
            ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
            ->select(
                'Recompensas.idRecompensa',
                'TiposRecompensas.nombre_TipoRecompensa as tipoRecompensa', // Traemos el nombre del tipo de recompensa
                'Recompensas.descripcionRecompensa',
                'Recompensas.costoPuntos_Recompensa',
                'Recompensas.stock_Recompensa' // Agregar el stock si es necesario
            )
            ->whereNull('Recompensas.deleted_at') // Excluir recompensas eliminadas
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

        $loginTecnico = DB::table('login_tecnicos')
            ->where('idTecnico', $request->input('idTecnico'))
            ->first();

        if (!$loginTecnico) {
            return response()->json(['message' => 'Técnico no encontrado'], 404);
        }

        if (!Hash::check($request->password, $loginTecnico->password)) {
            return response()->json(['message' => 'Contraseña incorrecta'], 400);
        }

        // Validar duplicados en la lista de oficios
        if (count($request->oficios) !== count(array_unique($request->oficios))) {
            return response()->json(['message' => 'No se permiten oficios duplicados'], 400);
        }

        DB::beginTransaction();
        try {
            $existingJobs = DB::table('TecnicosOficios')
                ->where('idTecnico', $request->input('idTecnico'))
                ->pluck('idOficio')
                ->toArray();

            $newJobs = array_diff($request->oficios, $existingJobs);
            $removedJobs = array_diff($existingJobs, $request->oficios);

            // Agregar nuevos oficios
            foreach ($newJobs as $oficioId) {
                DB::table('TecnicosOficios')->insert([
                    'idTecnico' => $request->input('idTecnico'),
                    'idOficio' => $oficioId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Eliminar oficios eliminados
            foreach ($removedJobs as $oficioId) {
                DB::table('TecnicosOficios')
                    ->where('idTecnico', $request->input('idTecnico'))
                    ->where('idOficio', $oficioId)
                    ->delete();
            }

            DB::commit();
            return response()->json(['message' => 'Oficios actualizados correctamente']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Error al actualizar oficios'], 500);
        }
    }


    public function getAvailableJobs()
    {
        // Obtener todas las recompensas desde la tabla 'Recompensas'
        $recompensas = DB::table('Oficios')
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