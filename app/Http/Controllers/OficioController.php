<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Throw_;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class OficioController extends Controller
{
    public function returnNuevoCodigoOficio() {
        $ultimoNumberOficioID = Oficio::max('idOficio');
        if (!$ultimoNumberOficioID) {
            return 'OFI-01';
        }
        $nuevoNumberOficioID = $ultimoNumberOficioID + 1;
        $nuevoCodigoOficio = 'OFI-'. str_pad($nuevoNumberOficioID, 2, '0', STR_PAD_LEFT);
        return $nuevoCodigoOficio;
    }

    public function create() {
        // Desde el modelo se agrega un campo dinámico codigoOficio (ejem: OFI-01)
        $oficios = Oficio::all(); 
        // Para depurar el códigoOficio
        //dd($oficios->pluck('codigoOficio'));
        
        foreach ($oficios as $oficio) {
            $oficio->codigoOficioNombre = $oficio->codigoOficio . " | ". $oficio->nombre_Oficio; //Ejemplo: OFI-02 | Carpintero
        }

        $nuevoCodigoOficio = $this->returnNuevoCodigoOficio();
        $oficiosEliminados = Oficio::onlyTrashed()->get();
 
        return view('dashboard.oficios', compact('oficios', 'nuevoCodigoOficio', 'oficiosEliminados'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'nombre_Oficio' => 'required|string',
                'descripcion_Oficio' => 'required|string',
            ]);
            DB::beginTransaction();
            $oficio = new Oficio($validatedData);
            $oficio->save(); // Guarda solo cuando estés seguro
            $messageStore = 'Recompensa guardada correctamente';
            DB::commit();
            return redirect()->route('oficios.create')->with('successOficioStore', $messageStore);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('oficios.create')->withErrors('Ocurrió un error al intentar crear el oficio. Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
        $validatedData = $request->validate([
            'idOficio' => 'required|exists:Oficios,idOficio',
            'descripcion_Oficio' => 'required|string',
        ]);

        $oficioSolicitado = Oficio::find($validatedData['idOficio']);
        $oficioSolicitado->update([
            'descripcion_Oficio' => $validatedData['descripcion_Oficio'],
        ]);
        
        //dd($oficioSolicitado);

        $messageUpdate = 'Oficio actualizado correctamente';
        return redirect()->route('oficios.create')->with('successOficioUpdate', $messageUpdate);
    }

    public function delete(Request $request) {
        $validatedData = $request->validate([
            'idOficio' => 'required|exists:Oficios,idOficio',
        ]);

        // Encuentra la recompensa usando el idRecompensa
        $oficio = Oficio::where("idOficio", $validatedData['idOficio'])->first();
    
        // Verifica si se encontró la recompensa
        if ($oficio) {
            // Aplica soft delete
            $oficio->delete();
            $messageDelete = 'Oficio eliminado correctamente';
        } else {
            $messageDelete = 'Oficio no encontrado';
        }
    
        return redirect()->route('oficios.create')->with('successOficioDelete', $messageDelete);
    }

    public function restaurar(Request $request) {
        try {
            $validatedData = $request->validate([
                'idOficio' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();
            
            //dd($validatedData);

            $oficioEliminado = Oficio::onlyTrashed()->where('idOficio', $validatedData['idOficio'])->first();
            
            if (!$oficioEliminado) {
                // Recompensa no encontrada o ya existe en registros activos
                return redirect()->route('oficios.create')->withErrors('Oficio no encontrado o ya restaurado.');
            }
            
            $oficioEliminado->restore();
            
            DB::commit();
            return redirect()->route('oficios.create')->with('successOficioRestaurado', 'Oficio restaurado correctamente.');
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('oficios.create')->withErrors('Ocurrió un error al intentar restaurar el oficio. Por favor, inténtelo de nuevo.');
        }
    }

    public function tabla()
    {
        $oficios = Oficio::all();

        // Agregar el índice de cada fila
        $oficios->each(function($oficio, $index) {
            $oficio->orderNum = $index + 1;  // Asegúrate de empezar en 1, no en 0
        });
        
        return DataTables::make($oficios)->toJson();
    }

    /*public function tabla(Request $request)
    {
        $query = Oficio::query();
        
        // Obtener los parámetros de DataTables para la paginación
        $start = $request->input('start');   // Offset de la página
        $length = $request->input('length'); // Número de registros por página
        
        // Obtener los datos paginados
        $oficios = $query->skip($start)->take($length)->get();

        $oficios->each(function($oficio, $index) {
            $oficio->orderNum = $index + 1;  // Asegúrate de empezar en 1, no en 0
        });

        // Obtener el número total de registros sin filtros
        $totalRecords = Oficio::count();

        // Obtener el número total de registros filtrados (si aplica)
        $filteredRecords = $query->count();

        // Responder a DataTables en el formato correcto
        return response()->json([
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $oficios
        ]);
    }*/

}
