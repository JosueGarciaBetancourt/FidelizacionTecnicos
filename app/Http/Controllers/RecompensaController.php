<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recompensa;
use App\Models\TipoRecompensa;
use Illuminate\Http\Request;
use Illuminate\Auth\Recaller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;


class RecompensaController extends Controller
{   
    public function generarIdRecompensa()
    {
        // Obtener el último ID de recompensa ordenado de forma descendente
        //$ultimaRecompensa = DB::table('recompensas')->orderBy('idRecompensa', 'desc')->first();
        $ultimaRecompensaID = Recompensa::max('idRecompensa');

        // Si la tabla está vacía, comenzar desde "RECOM-001"
        if (!$ultimaRecompensaID) {
            Log::info('Generando ID inicial RECOM-001');
            return 'RECOM-001';
        }

        // Extraer el número de la cadena del último ID de recompensa
        $strNumRecompensa = substr($ultimaRecompensaID, -3); // Obtiene los últimos 3 caracteres

        // Convertir la parte numérica a entero
        $intNumRecompensa = intval($strNumRecompensa);

        // Incrementar el número para generar el siguiente idRecompensa
        $nuevoNumero = $intNumRecompensa + 1;

        // Formatear el nuevo número con ceros a la izquierda
        $nuevoIdRecompensa = 'RECOM-'. str_pad($nuevoNumero, 3, '0', STR_PAD_LEFT);
        
        Log::info('Nuevo ID: '. $nuevoIdRecompensa);

        return $nuevoIdRecompensa;
    }

    public function returnNuevoCodigoTipoRecompensa() {
        $ultimoNumberTipoRecompensaID = TipoRecompensa::max('idTipoRecompensa');
        if (!$ultimoNumberTipoRecompensaID) {
            return 'TIPO-01';
        }
        $nuevoNumberTipoRecompensaID = $ultimoNumberTipoRecompensaID + 1;
        $nuevoCodigoTipoRecompensa= 'TIPO-'. str_pad($nuevoNumberTipoRecompensaID, 2, '0', STR_PAD_LEFT);
        return $nuevoCodigoTipoRecompensa;
    }

    public function returnArrayNombresTiposRecompensas()
    {
        // Obtener todos los tipos de recompensas y filtrar
        return TipoRecompensa::all()
                ->reject(function ($tipoRecompensa) {
                    return $tipoRecompensa->idTipoRecompensa == 1;
                })
                ->pluck('nombre_TipoRecompensa')
                ->values(); // Reindexar
    }


    public function create()
    {
        // Obtener la última recompensa para generar el nuevo ID
        $idNuevaRecompensa = $this->generarIdRecompensa();
        $idNuevoTipoRecompensa = $this->returnNuevoCodigoTipoRecompensa();

        // Obtener todas las recompensas activas (Inicialmente "RECOM-000, Efectivo" está inactivo)
        $recompensas = Recompensa::query()
                                ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                                ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa'])
                                ->whereNull('Recompensas.deleted_at')
                                ->orderBy('Recompensas.idRecompensa', 'ASC') 
                                ->get(); 

        // Obtener todas las recompensas no activas (soft deleted) con sus tipos
        $recompensasEliminadas = Recompensa::onlyTrashed()
                                            ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                                            ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa'])
                                            ->orderBy('Recompensas.idRecompensa', 'ASC') 
                                            ->get();

        $tiposRecompensas = TipoRecompensa::all()->reject(function ($recompensa) {
                                return $recompensa->idTipoRecompensa == 1;
                            })->values(); // Reindexa los índices de la colección

        // dd($tiposRecompensas->pluck('codigoTipoRecompensa')); 

        $nombresTiposRecompensas = $this->returnArrayNombresTiposRecompensas();

        //dd($nombresTiposRecompensas);

        return view('dashboard.recompensas', compact('recompensas', 'tiposRecompensas', 'idNuevaRecompensa', 
                                                    'idNuevoTipoRecompensa', 'recompensasEliminadas', 'nombresTiposRecompensas'));
    }
    
    public function store(Request $request) 
    {
        try {
            // Validar los datos de entrada
            $validatedData = $request->validate([
                'tipoRecompensa' => 'required|string',
                'descripcionRecompensa' => 'required|string|max:255',
                'costoPuntos_Recompensa' => 'required|numeric|min:0',
                'stock_Recompensa' => 'required|numeric|min:1',
            ]);

            DB::beginTransaction();

            // Verificar si el tipo de recompensa existe
            $idTipoRecompensa = TipoRecompensa::where('nombre_TipoRecompensa', $validatedData['tipoRecompensa'])->pluck('idTipoRecompensa')->first();
            if (!$idTipoRecompensa) {
                return redirect()->route('recompensas.create')
                    ->withErrors(['tipoRecompensa' => 'El tipo de recompensa seleccionado no existe.']);
            }

            // Generar ID de la nueva recompensa
            $idNuevaRecompensa = $this->generarIdRecompensa();

            // Verificar si ya existe una recompensa con la misma descripción
            $existeRecompensa = Recompensa::where('descripcionRecompensa', $validatedData['descripcionRecompensa'])->exists();
            if ($existeRecompensa) {
                return redirect()->route('recompensas.create')
                    ->withErrors(['descripcionRecompensa' => 'Ya existe una recompensa con esta descripción.']);
            }

            // Crear los datos de recompensa
            $recompensaData = [
                'idRecompensa' => $idNuevaRecompensa,
                'idTipoRecompensa' => $idTipoRecompensa,
                'descripcionRecompensa' => $validatedData['descripcionRecompensa'],
                'costoPuntos_Recompensa' => $validatedData['costoPuntos_Recompensa'],
                'stock_Recompensa' => $validatedData['stock_Recompensa'],
            ];

            // Crear recompensa en la base de datos
            Recompensa::create($recompensaData);

            DB::commit();

            // Redirigir con éxito
            $messageStore = 'Recompensa guardada correctamente.';
            return redirect()->route('recompensas.create')->with('successRecompensaStore', $messageStore);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('recompensas.create')
                ->withErrors('Ocurrió un error al guardar la recompensa: ' . $e->getMessage());
        }
    }

    public function update(Request $request) 
    {
        $recompensaSolicitada = Recompensa::find($request->idRecompensa);
        // Actualizar los campos
        $recompensaSolicitada->update([
            'costoPuntos_Recompensa' => $request->costoPuntos_Recompensa,
            'stock_Recompensa' => $request->stock_Recompensa,
        ]);

        $messageUpdate = 'Recompensa actualizada correctamente';
        
        return redirect()->route('recompensas.create')->with('successRecompensaUpdate', $messageUpdate);
    }

    public function delete(Request $request) 
    {
        // Encuentra la recompensa usando el idRecompensa
        $recompensa = Recompensa::where("idRecompensa", $request->idRecompensa)->first();
    
        // Verifica si se encontró la recompensa
        if ($recompensa) {
            // Aplica soft delete
            $recompensa->delete();
    
            $messageDelete = 'Recompensa eliminada correctamente';
        } else {
            $messageDelete = 'Recompensa no encontrada';
        }
    
        return redirect()->route('recompensas.create')->with('successRecompensaDelete', $messageDelete);
    }

    public function restaurar(Request $request) {
        try {
            $validatedData = $request->validate([
                'idRecompensa' => 'required|string|max:9',
            ]);
            
            // Iniciar transacción
            DB::beginTransaction();
            
            //dd($validatedData['idRecompensa']);

            // Obtener la recompensa eliminada lógicamente
            $recompensaEliminada = Recompensa::onlyTrashed()->where('idRecompensa', $validatedData['idRecompensa'])->first();
            
            if (!$recompensaEliminada) {
                // Recompensa no encontrada o ya existe en registros activos
                return redirect()->route('recompensas.create')->withErrors('Recompensa no encontrada o ya restaurada.');
            }
            
            // Restaurar la recompensa
            $recompensaEliminada->restore();
            
            // Confirmar transacción
            DB::commit();
            return redirect()->route('recompensas.create')->with('successRecompensaRestaurada', 'Recompensa restaurada correctamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            
            return redirect()->route('recompensas.create')->withErrors('Ocurrió un error al intentar restaurar la recompensa. Por favor, inténtelo de nuevo.');
        }
    }

    public static function updateStockByIdRecompensaCantidad($idRecompensa, $cantidad) {
        $recompensa = Recompensa::where('idRecompensa', $idRecompensa)->first();
    
        if ($recompensa) {
            $stockActual = self::calcStockActualByIdRecompensa($idRecompensa);
            $nuevoStock = $stockActual - $cantidad;
    
            $recompensa->update([
                'stock_Recompensa' => $nuevoStock,
            ]);
        } else {
            // Manejar el caso en el que la recompensa no se encuentra
            throw new Exception("Recompensa no encontrada con id: {$idRecompensa}");
        }
    }
    
    public static function calcStockActualByIdRecompensa($idRecompensa) {
        // Obtener solo el valor de stock_Recompensa
        $recompensa = Recompensa::where('idRecompensa', $idRecompensa)->first();
        
        if ($recompensa) {
            //dd($recompensa->stock_Recompensa);
            return $recompensa->stock_Recompensa;
        } else {
            throw new Exception("Recompensa no encontrada con id: {$idRecompensa}");
        }
    }

    public function storeTipoRecompensa(Request $request) 
    {
        try {
            $validatedData = $request->validate([
                'nombre_TipoRecompensa' => 'required|string',
            ]);
    
            DB::beginTransaction();
    
            // Crear una instancia pero no guardar
            $tipoRecompensa = new TipoRecompensa($validatedData);
            // dd($tipoRecompensa); // Aquí puedes inspeccionar el modelo sin que afecte el ID
            $tipoRecompensa->save(); // Guarda solo cuando estés seguro
            DB::commit();
            $messageStore = 'Tipo de recompensa guardado correctamente';
            return redirect()->route('recompensas.create')->with('successTipoRecompensaStore', $messageStore);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('recompensas.create')->withErrors('Ocurrió un error al intentar crear el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function updateTipoRecompensa(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
                'nombre_TipoRecompensa' => 'required|string',
            ]);
            DB::beginTransaction();
            $tipoRecompensaSolicitado = TipoRecompensa::find($validatedData['idTipoRecompensa']);
            $tipoRecompensaSolicitado->update([
                'nombre_TipoRecompensa' => $validatedData['nombre_TipoRecompensa'],
            ]);
            // dd($tipoRecompensaSolicitado);
            $messageUpdate = 'Tipo de recompensa actualizado correctamente';
            DB::commit();
            return redirect()->route('recompensas.create')->with('successTipoRecompensaUpdate', $messageUpdate);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('recompensas.create')->withErrors('Ocurrió un error al intentar actualizar el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function deleteTipoRecompensa(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|exists:TiposRecompensas,idTipoRecompensa',
            ]);
            DB::beginTransaction();
            $tipoRecompensa = TipoRecompensa::where("idTipoRecompensa", $validatedData['idTipoRecompensa'])->first();
            if ($tipoRecompensa) {
                $tipoRecompensa->forceDelete(); // Eliminar registro de la BD físicamente
                $messageDelete = 'Tipo de recompensa eliminado correctamente';
            } else {
                $messageDelete = 'Tipo de recompensa no encontrado';
            }
            DB::commit();
            return redirect()->route('recompensas.create')->with('successTipoRecompensaDelete', $messageDelete);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('recompensas.create')->withErrors('Ocurrió un error al intentar eliminar el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }
}
