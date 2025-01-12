<?php

namespace App\Http\Controllers;
use App\Models\Oficio;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use App\Models\Login_Tecnico;
use App\Models\TecnicoOficio;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;

class TecnicoController extends Controller
{   
    public static function returnModelsTecnicosWithOficios()
    {
        // Obtener todos los técnicos con sus oficios en una sola consulta
        $tecnicos = Tecnico::with(['oficios' => function ($query) {
            $query->select('Oficios.idOficio', 'nombre_Oficio');
        }])->get();

        foreach ($tecnicos as $tecnico) {
            $oficios = $tecnico->oficios;

            // Usar collections de Laravel para manipular los datos de manera más eficiente
            if ($oficios->isNotEmpty()) {
                $tecnico->idsOficioTecnico = '[' . $oficios->pluck('idOficio')->implode(',') . ']';
                $tecnico->idNameOficioTecnico = $oficios->map(function($oficio) {
                    return "{$oficio->idOficio}-{$oficio->nombre_Oficio}";
                })->implode(' | ');
            } else {
                $tecnico->idsOficioTecnico = 'No tiene oficios';
                $tecnico->idNameOficioTecnico = 'No tiene oficios';
            }
        }

        return $tecnicos;
    }

    /* public function returnModelsDeletedTecnicosWithOficios() {
        $tecnicos = Tecnico::onlyTrashed()->with(['oficios' => function ($query) {
            $query->select('Oficios.idOficio', 'nombre_Oficio');
        }])->get();

        foreach ($tecnicos as $tecnico) {
            $oficios = $tecnico->oficios;

            // Usar collections de Laravel para manipular los datos de manera más eficiente
            if ($oficios->isNotEmpty()) {
                $tecnico->idsOficioTecnico = '[' . $oficios->pluck('idOficio')->implode(',') . ']';
                $tecnico->idNameOficioTecnico = $oficios->map(function($oficio) {
                    return "{$oficio->idOficio}-{$oficio->nombre_Oficio}";
                })->implode(' | ');
            } else {
                $tecnico->idsOficioTecnico = 'No tiene oficios';
                $tecnico->idNameOficioTecnico = 'No tiene oficios';
            }
        }

        return $tecnicos;
    } */

    public function returnArrayIdsNombresOficios() {
        // Obtener todos los oficios de la BD
        $oficios = Oficio::all();
        // Obtener todos los nombres de los oficios 
        $arrayIdsNombreOficios = [];
        foreach ($oficios as $oficio) {
            $arrayIdsNombreOficios[] = $oficio->idOficio . "-" . $oficio->nombre_Oficio;
        }
        return $arrayIdsNombreOficios;
    }

    public function create()
    {   
        /*$tecnicos = $this->returnModelsTecnicosWithOficios(); 
        $tecnicosBorrados = $this->returnModelsDeletedTecnicosWithOficios();*/
        $tecnicosBorrados = Tecnico::onlyTrashed()->get();
        $idsNombresOficios = $this->returnArrayIdsNombresOficios(); // 1-Albañil | ...*/
        //dd($tecnicosBorrados->pluck('idsOficioTecnico'));
        //dd($tecnicosBorrados->pluck('idNameOficioTecnico'));
        return view('dashboard.tecnicos', compact('tecnicosBorrados', 'idsNombresOficios'));
    }

    function store(Request $request) 
    {
        try {
            // Primera validación, solo para asegurar que es un JSON válido
            $validatedData = $request->validate([
                'idTecnico' => 'required|max:8', // Validación de seguridad adicional sugerida
                'nombreTecnico' => 'required|string|max:100',
                'celularTecnico' => 'required|string|regex:/^[0-9]{9}$/', // Verifica que tenga exactamente 9 dígitos
                'fechaNacimiento_Tecnico' => 'required|date', // Valida que sea una fecha
                'idOficioArray' => 'required|json', // Valida que sea un JSON válido
            ]);

            // Decodifica el JSON a un array
            $idOficioArray = json_decode($validatedData['idOficioArray'], true);

            // Verifica que sea un array y aplica la validación para cada elemento
            if (!is_array($idOficioArray) || !collect($idOficioArray)->every(fn($id) => is_int($id) && $id > 0)) {
                throw new \Exception("El campo idOficioArray debe ser un array de ENTEROS POSITIVOS.");
            }

            // Iniciar la transacción
            DB::beginTransaction();

            // Obtener el origen de la solicitud
            $origin = $request->input('origin'); // Con JS se modifica el valor del input en modalAgregarNuevoTecnico.blade.php
            
            // Comprobar si el técnico fue borrado con soft delete
            $tecnicoEliminado = Tecnico::onlyTrashed()->where('idTecnico', $validatedData['idTecnico'])->first();

            if ($tecnicoEliminado) {
                // Restaurar el técnico si ha sido eliminado lógicamente
                $tecnicoEliminado->restore();
                $rango = $this->getRango($tecnicoEliminado->historicoPuntos_Tecnico);
                $tecnicoData = array_merge($validatedData, ['rangoTecnico' => $rango]);
                $tecnicoEliminado->update($tecnicoData);
            } else {
                // Crear un nuevo técnico si no existe
                $tecnico = Tecnico::create([
                    'idTecnico' => $validatedData['idTecnico'],
                    'nombreTecnico' => $validatedData['nombreTecnico'],
                    'celularTecnico' => $validatedData['celularTecnico'],
                    'fechaNacimiento_Tecnico' => $validatedData['fechaNacimiento_Tecnico'],
                ]);

                // Guardar oficios en la tabla TecnicosOficios
                foreach ($idOficioArray as $idOficio) {
                    TecnicoOficio::create([
                        'idTecnico' => $validatedData['idTecnico'],
                        'idOficio' => $idOficio,
                    ]);
                }

                // Validar y crear un nuevo login para el técnico
                $validatedDatLoginTecnico = $request->validate([
                    'idTecnico' => 'required|unique:login_tecnicos|max:8',
                ]);

                // Contraseña por defecto (DNI) que podrá ser cambiada desde la APP
                Login_Tecnico::create([
                    'idTecnico' => $validatedDatLoginTecnico['idTecnico'],
                    'password' => bcrypt($validatedDatLoginTecnico['idTecnico']),
                ]);
            }

            // Confirmar la transacción
            DB::commit();

        } catch (\Exception $e) {
            // Si ocurre un error, revertir la transacción
            DB::rollBack();
            return redirect()->back()->withErrors("Error en la validación o inserción del formulario: " . $e->getMessage());
        }

        // Redirigir basado en el origen
        switch ($origin) { 
            case 'ventasIntermediadas.create':
                return redirect()->route('ventasIntermediadas.create')->with('successTecnicoStore', 'Técnico agregado exitósamente desde ventas.');
            default:
                return redirect()->route('tecnicos.create')->with('successTecnicoStore', 'Técnico agregado exitósamente.');
        }
    }

    function update(Request $request) 
    {
        try {
            // Primera validación, solo para asegurar que es un JSON válido
            $validatedData = $request->validate([
                'idTecnico' => 'required|max:8', // Validación de seguridad adicional sugerida
                'celularTecnico' => 'required|string|regex:/^[0-9]{9}$/', // Verifica que tenga exactamente 9 dígitos
                'idOficioArray' => 'required|json', // Valida que sea un JSON válido
            ]);
        
            // Decodifica el JSON a un array
            $idOficioArray = json_decode($validatedData['idOficioArray'], true);
        
            // Verifica que sea un array y aplica la validación para cada elemento
            if (!is_array($idOficioArray) || !collect($idOficioArray)->every(fn($id) => is_int($id) && $id > 0)) {
                throw new \Exception("El campo idOficioArray debe ser un array de ENTEROS POSITIVOS.");
            }
        
            // Datos validados para la tabla Tecnicos
            $validatedDataTecnico = [
                'idTecnico' => $validatedData['idTecnico'],
                'celularTecnico' => $validatedData['celularTecnico'],
            ];
        
            // Datos validados para la tabla TecnicosOficios
            $validatedDataTecnicoOficio = [
                'idTecnico' => $validatedData['idTecnico'],
                'idOficioArray' => $idOficioArray, // Array decodificado y validado
            ];
        
            // dd($validatedDataTecnico, $validatedDataTecnicoOficio);
        } catch (\Exception $e) {
            dd("Error en la validación del formulario Editar Técnico: " . $e->getMessage());
        }

        $tecnico = Tecnico::find($validatedDataTecnico['idTecnico']);
        $rango = $this->getRango($tecnico->historicoPuntos_Tecnico);
        // Actualizar en Tecnicos
        $tecnico->update([
            'celularTecnico' => $validatedDataTecnico['celularTecnico'],
            'rangoTecnico' => $rango,
        ]);

        // Actualizar la relación en la tabla TecnicosOficios
        // Primero, eliminar los registros existentes para evitar duplicados
        TecnicoOficio::where('idTecnico', $validatedDataTecnicoOficio['idTecnico'])->delete();
        
        // Insertar los nuevos oficios
        foreach ($idOficioArray as $idOficio) {
            TecnicoOficio::create([
                'idTecnico' => $validatedDataTecnicoOficio['idTecnico'],
                'idOficio' => $idOficio,
            ]);
        }

        $messageUpdate = 'Técnico actualizado correctamente';

        return redirect()->route('tecnicos.create')->with('successTecnicoUpdate', $messageUpdate);
    }

    public function delete(Request $request) 
    {
        // Encuentra el técnico usando el idTécnico
        $tecnico = Tecnico::where("idTecnico", $request->idTecnico)->first();
    
        // Verifica si se encontró el técnico
        if ($tecnico) {
            // Aplica soft delete
            $tecnico->delete();
    
            $messageDelete = 'Técnico eliminado correctamente';
        } else {
            $messageDelete = 'Técnico no encontrado';
        }
    
        return redirect()->route('tecnicos.create')->with('successTecnicoDelete', $messageDelete);
    }

    public function recontratar(Request $request)
    {
        try {
            // Primera validación, solo para asegurar que es un JSON válido
            $validatedData = $request->validate([
                'idTecnico' => 'required|max:8', // Validación de seguridad adicional sugerida
                'celularTecnico' => 'required|string|regex:/^[0-9]{9}$/', // Verifica que tenga exactamente 9 dígitos
                'idOficioArray' => 'required|json', // Valida que sea un JSON válido
            ]);
        
            // Decodifica el JSON a un array
            $idOficioArray = json_decode($validatedData['idOficioArray'], true);
        
            // Verifica que sea un array y aplica la validación para cada elemento
            if (!is_array($idOficioArray) || !collect($idOficioArray)->every(fn($id) => is_int($id) && $id > 0)) {
                throw new \Exception("El campo idOficioArray debe ser un array de ENTEROS POSITIVOS.");
            }
        
            // Datos validados para la tabla Tecnicos
            $validatedDataTecnico = [
                'idTecnico' => $validatedData['idTecnico'],
                'celularTecnico' => $validatedData['celularTecnico'],
            ];
        
            // Datos validados para la tabla TecnicosOficios
            $validatedDataTecnicoOficio = [
                'idTecnico' => $validatedData['idTecnico'],
                'idOficioArray' => $idOficioArray, // Array decodificado y validado
            ];
        
            //dd($validatedDataTecnico, $validatedDataTecnicoOficio);
        } catch (\Exception $e) {
            dd("Error en la validación del formulario Editar Técnico: " . $e->getMessage());
        }

        // Iniciar transacción
        DB::beginTransaction();
        
        try {
            // Obtener el técnico eliminado
            $tecnicoEliminado = Tecnico::onlyTrashed()->where('idTecnico', $validatedDataTecnico['idTecnico'])->first();
            
            if (!$tecnicoEliminado) {
                return redirect()->route('tecnicos.create')->withErrors('Técnico no encontrado o ya activo.');
            }
            
            // Restaurar el técnico
            $tecnicoEliminado->restore();
            
            // Calcular el rango
            $rango = $this->getRango($tecnicoEliminado->historicoPuntos_Tecnico);
            
            // Actualizar los datos del técnico
            $tecnicoData = array_merge($validatedDataTecnico, ['rangoTecnico' => $rango]);
            $tecnicoEliminado->update($tecnicoData);

            // Actualizar la relación en la tabla TecnicosOficios
            // Primero, eliminar los registros existentes para evitar duplicados
            TecnicoOficio::where('idTecnico', $validatedDataTecnicoOficio['idTecnico'])->delete();
            
            // Insertar los nuevos oficios
            foreach ($idOficioArray as $idOficio) {
                TecnicoOficio::create([
                    'idTecnico' => $validatedDataTecnicoOficio['idTecnico'],
                    'idOficio' => $idOficio,
                ]);
            }

            //dd($tecnicoEliminado);
            // Confirmar transacción
            DB::commit();
            return redirect()->route('tecnicos.create')->with('successTecnicoRecontratadoStore', 'Técnico agregado exitosamente.');
        } catch (\Exception $e) {
            // Revertir transacción si hay un error
            DB::rollBack();
            // Redirigir con mensaje de error
            return redirect()->route('tecnicos.create')->withErrors('Ocurrió un error al intentar recontratar al técnico. Por favor, inténtelo de nuevo.');
        }
    }

    public function getRango(int $puntos): string
    {
        if ($puntos < 24000) {
            return 'Plata';
        } elseif ($puntos >= 24000 && $puntos < 60000) {
            return 'Oro';
        } else {
            return 'Black';
        }
    }

    public static function updatePuntosActualesTecnicoById($idTecnico) {
        // Actualizar los puntos actuales del técnico
        $tecnico = Tecnico::where('idTecnico', $idTecnico)->first();
        $nuevosPuntos = TecnicoController::calcPuntosActualesByIdtecnico($idTecnico);
        $tecnico->update([
            'totalPuntosActuales_Tecnico' => $nuevosPuntos,
        ]);
    }

    public static function updatePuntosHistoricosTecnicoById($idTecnico) {
        $tecnico = Tecnico::where('idTecnico', $idTecnico)->first();
        $nuevoHistoricoPuntos = TecnicoController::calcPuntosHistoricosByIdtecnico($idTecnico);
        $tecnico->update([
            'historicoPuntos_Tecnico' => $nuevoHistoricoPuntos,
        ]);
    }
    
    public static function calcPuntosActualesByIdtecnico($idTecnico) {
        // Suma de puntos de ventas intermediadas con estado "En espera" y "Redimido (parcial)"
        $sumaPuntosActuales = VentaIntermediada::where('idTecnico', $idTecnico)
                                            ->whereIn('idEstadoVenta', [1, 2])
                                            ->sum('puntosActuales_VentaIntermediada');
        //dd($sumaPuntosActuales);
        return $sumaPuntosActuales;
    }

    public static function calcPuntosHistoricosByIdtecnico($idTecnico) {
        $sumaPuntosTotales = VentaIntermediada::where('idTecnico', $idTecnico)
                                                ->sum('puntosGanados_VentaIntermediada');
        //dd($sumaPuntosTotales);
        return $sumaPuntosTotales;
    }

    public function getOficiosByIdTecnico($idTecnico) {
        $arrayIdOficios = TecnicoOficio::where('idTecnico', $idTecnico)->pluck('idOficio');
    
        if ($arrayIdOficios->isNotEmpty()) {
            $arrayNombreOficios = [];
    
            foreach ($arrayIdOficios as $idOficio) {
                $oficio = Oficio::where('idOficio', $idOficio)->first();
        
                if ($oficio) { 
                    $arrayNombreOficios[] = $oficio->nombre_Oficio;
                }
            }
        
            return $arrayNombreOficios;
        }
    
        return [];  
    }

    public function returnArrayTecnicosWithOficios() {
        // Cargar técnicos con sus oficios en una sola consulta
        $tecnicos = Tecnico::with('oficios')->get();
        $index = 1;

        // Mapear los datos para transformarlos
        $data = $tecnicos->map(function ($tecnico) use (&$index) {
            // Obtener nombres de los oficios y concatenarlos
            $oficios = $tecnico->oficios->pluck('nombre_Oficio')->implode(' / ') ?: 'No tiene';
    
            return [
                'index' => $index++,
                'idTecnico' => $tecnico->idTecnico,
                'nombreTecnico' => $tecnico->nombreTecnico,
                'oficioTecnico' => $oficios,
                'celularTecnico' => $tecnico->celularTecnico,
                'fechaNacimiento_Tecnico' => $tecnico->fechaNacimiento_Tecnico,
                'totalPuntosActuales_Tecnico' => $tecnico->totalPuntosActuales_Tecnico,
                'historicoPuntos_Tecnico' => $tecnico->historicoPuntos_Tecnico,
                'rangoTecnico' => $tecnico->rangoTecnico,
            ];
        });
    
        return $data->toArray();
    }

    public function tabla(Request $request) {
        if ($request->ajax()) {
            $tecnicosWithOficios = $this->returnArrayTecnicosWithOficios();
            if (empty($tecnicosWithOficios)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No se encontraron técnicos.'
                ], 204);
            }
    
            return DataTables::of($tecnicosWithOficios)
                ->addColumn('actions', 'tables.tecnicoActionColumn')
                ->rawColumns(['actions'])
                ->make(true);
        }
    
        return abort(403);
    }

    public function verificarExistenciaTecnico(Request $request) {
        try {
            // Validar entrada
            $validated = $request->validate([
                'idTecnico' => 'required|digits:8'
            ]);
    
            // Verificar existencia del técnico
            $exists = Tecnico::where('idTecnico', $validated['idTecnico'])->exists();
    
            return response()->json(['exists' => $exists]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Validación fallida', 'details' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Registrar detalles del error para depuración
            /* Log::info('Error en verificarTecnico', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */

            return response()->json(['error' => 'Error al verificar el técnico', 'details' => $e->getMessage()], 500);
        }
    }

    public function getTecnicoByIdNombre(Request $request) {
        try {
            $idNombreTecnico = $request->input('idNombreTecnico', '');
            
            // Validar si el filtro está vacío
            if (empty($idNombreTecnico)) {
                return response()->json(['message' => 'No se ingresó un idNombreTecnico.',
                ], 404);
            }

            // Validar si el valor contiene el formato 'idTecnico | nombreTecnico'
            if (strpos($idNombreTecnico, ' | ') === false) {
                return response()->json(['message' => 'Formato incorrecto. El formato debe ser "idTecnico | nombreTecnico"'], 404);
            }
    
            // Separar el valor de 'idNombreTecnico' en 'idTecnico' y 'nombreTecnico'
            list($idTecnico, $nombreTecnico) = explode(' | ', $idNombreTecnico);
        
            // Buscar al técnico por id y nombre
            $tecnicoBuscado = Tecnico::where('idTecnico', $idTecnico)
                ->where('nombreTecnico', $nombreTecnico)
                ->first();
    
            // Verificar si el técnico fue encontrado
            if (!$tecnicoBuscado) {
                return response()->json(['message' => 'Técnico no encontrado'], 404);
            }
        
            // Retornar respuesta exitosa con los datos del técnico
            return response()->json(['tecnicoBuscado' => $tecnicoBuscado], 200);
        
        } catch (\Exception $e) {
            // Registrar detalles del error para depuración
            /* Log::info('Error en getTecnicoByIdNombre', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */
            
            // Responder con mensaje de error detallado
            return response()->json([
                'message' => 'Ocurrió un error en el servidor. Por favor intente nuevamente más tarde.',
                'error_details' => $e->getMessage()  // Agregar detalles del error si es necesario
            ], 500);
        }
    }

    public function getPaginatedTecnicos(Request $request) {
        try {
            $currentPage = $request->input('page', 1); // Por defecto, carga la página 1

            // Establecer la página actual en la paginación
            Paginator::currentPageResolver(function () use ($currentPage) {
                return $currentPage;
            });
            
            // Paginar los resultados
            $tecnicosPaginatedDB = Tecnico::paginate(6);

            // Verificar si se encontraron técnicos
            if ($tecnicosPaginatedDB->total() === 0) {
                return response()->json([
                    'data' => null,
                    'message' => 'No hay técnicos registrados aún',
                ], 200);
            }

            // Controller::printJSON($tecnicosPaginatedDB->items());

            return response()->json([
                'data' => $tecnicosPaginatedDB->items(),
                'current_page' => $tecnicosPaginatedDB->currentPage(),
                'last_page' => $tecnicosPaginatedDB->lastPage(),
                'total' => $tecnicosPaginatedDB->total(),
            ], 200);
        } catch (\Exception $e) {
            /* Log::info('Error en getPaginatedTecnicos', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */
            return response()->json(['error' => 'Error al obtener los técnicos paginados', 'details' => $e->getMessage()], 500);
        }
    }

    public function getFilteredTecnicos(Request $request) {
        try {
            $filter = $request->input('filter', '');
    
            // Construir la consulta utilizando Eloquent de manera segura
            $query = Tecnico::query();
    
            $query->whereRaw("CONCAT(idTecnico, ' | ', nombreTecnico) LIKE ?", ["%$filter%"]);

            // Obtener los resultados paginados
            $tecnicosDB = $query->paginate(6);

            // Verificar si hay resultados
            if ($tecnicosDB->isEmpty()) {
                return response()->json([
                    'data' => [],
                    'message' => 'No se encontraron técnicos.',
                ], 404);
            }

            return response()->json([
                'data' => $tecnicosDB->items(),
                'current_page' => $tecnicosDB->currentPage(),
                'total' => $tecnicosDB->total(),
                'last_page' => $tecnicosDB->lastPage(),
                'per_page' => $tecnicosDB->perPage(),
            ], 200);
        } catch (\Exception $e) {
            /* // Registrar detalles del error para depuración
                Log::info('Error en getFilteredTecnicos', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */

            return response()->json([
                'message' => 'Error al filtrar técnicos: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function restorePassword(Request $request)
    {
        try {
            $idTecnico = $request->input('idTecnico', '');

            if (empty($idTecnico)) {
                return response()->json([
                    'error' => 'Error: No se envió un idTecnico válido.',
                ], 400); // Código 400 para errores de cliente
            }

            // Buscar el técnico
            $loginTecnico = Login_Tecnico::find($idTecnico);

            if (!$loginTecnico) {
                return response()->json([
                    'error' => 'Error: No se encontró un técnico con el ID proporcionado.',
                ], 404); // Código 404 para "No encontrado"
            }
            
            if (Hash::check($loginTecnico->idTecnico, $loginTecnico->password)) {
                return response()->json([
                    'message' => 'La contraseña ya está restaurada.',
                    'wasRestored' => true,
                ], 200);
            }
            
            // Actualizar el técnico
            $loginTecnico->update([
                'password' => Hash::make($loginTecnico->idTecnico),
                'isFirstLogin' => 0,
            ]);

            return response()->json([
                'message' => 'La contraseña del técnico fue restaurada exitosamente.',
                'wasRestored' => false,
            ], 200);

        } catch (\Exception $e) {
            /* Log::info('Error en restorePassword', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]); */

            // Manejo de errores
            return response()->json([
                'error' => 'Error interno al restaurar la contraseña.',
                // Eliminar 'details' para entornos de producción
                'details' => env('APP_DEBUG') ? $e->getMessage() : 'Consulte con el administrador.',
            ], 500); // Código 500 para errores internos del servidor
        }
    }
}
