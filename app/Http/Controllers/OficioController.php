<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Throw_;

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
        /* foreach ($oficios as $oficio) {
            dd($oficio->codigoOficio); 
        }*/
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
            $oficio = Oficio::create($validatedData);
            //dd($oficio);
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
}
