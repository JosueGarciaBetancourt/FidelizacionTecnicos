<?php

namespace App\Http\Controllers;

use App\Models\Recompensa;
use Illuminate\Http\Request;
use App\Models\TipoRecompensa;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SystemNotificationController;

class TipoRecompensaController extends Controller
{
    public static function returnNuevoCodigoTipoRecompensa() {
        $ultimoNumberTipoRecompensaID = TipoRecompensa::max('idTipoRecompensa');
        if (!$ultimoNumberTipoRecompensaID) {
            return 'TIPO-01';
        }
        $nuevoNumberTipoRecompensaID = $ultimoNumberTipoRecompensaID + 1;
        $nuevoCodigoTipoRecompensa= 'TIPO-'. str_pad($nuevoNumberTipoRecompensaID, 2, '0', STR_PAD_LEFT);
        return $nuevoCodigoTipoRecompensa;
    }

    public function create() {
        $nuevoIdTipoRecompensa = TipoRecompensa::max('idTipoRecompensa') ? TipoRecompensa::max('idTipoRecompensa') + 1 : 1;
        $nuevoCodigoTipoRecompensa = self::returnNuevoCodigoTipoRecompensa();

        $tiposRecompensas = TipoRecompensa::all()->reject(function ($recompensa) {
            return $recompensa->idTipoRecompensa == 1;
        })->values(); // Reindexa los índices de la colección

        $recompensas = Recompensa::query()
                        ->join('TiposRecompensas', 'Recompensas.idTipoRecompensa', '=', 'TiposRecompensas.idTipoRecompensa')
                        ->select(['Recompensas.*', 'TiposRecompensas.nombre_TipoRecompensa'])
                        ->whereNull('Recompensas.deleted_at')
                        ->orderBy('Recompensas.idRecompensa', 'ASC') 
                        ->get(); 
                        
        // dd($tiposRecompensas);

        // Obtener las notificaciones
        $notifications = SystemNotificationController::getActiveNotifications();
        
        return view('dashboard.tiposRecompensas', compact('nuevoIdTipoRecompensa', 'nuevoCodigoTipoRecompensa', 'tiposRecompensas', 
                                                        'recompensas', 'notifications'));
    }

    public function store(Request $request) {
        try {
            $validatedData = $request->validate([
                'idTipoRecompensa' => 'required|numeric',
                'nombre_TipoRecompensa' => 'required|string',
                'descripcion_TipoRecompensa' => 'nullable|string',
                'colorTexto_TipoRecompensa' => 'required|string|max:7', // Formato hexadecimal
                'colorFondo_TipoRecompensa' => 'required|string|max:7',
            ]);
    
            DB::beginTransaction();
    
            // Crear una instancia pero no guardar
            $tipoRecompensa = new TipoRecompensa($validatedData);
            // dd($tipoRecompensa); // Aquí puedes inspeccionar el modelo sin que afecte el ID
            $tipoRecompensa->save(); // Guarda solo cuando estés seguro
            DB::commit();
            $messageStore = 'Tipo de recompensa guardado correctamente';
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaStore', $messageStore);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar crear el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function update(Request $request) {
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
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaUpdate', $messageUpdate);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar actualizar el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

    public function delete(Request $request) {
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
            return redirect()->route('tiposRecompensas.create')->with('successTipoRecompensaDelete', $messageDelete);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            return redirect()->route('tiposRecompensas.create')->withErrors('Ocurrió un error al intentar eliminar el tipo de recompensa. 
                                                                        Por favor, inténtelo de nuevo.');
        }
    }

}
