<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recompensa;
use Illuminate\Auth\Recaller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

    public function create()
    {
        // Obtener la última recompensa para generar el nuevo ID
        $idNuevaRecompensa = $this->generarIdRecompensa();
        
        // Obtener todas las recompensas activas
        $recompensas = Recompensa::all();
        
        // Obtener todas las recompensas excepto la primera
        $recompensasWithoutFirst = $recompensas->skip(1);
        
        return view('dashboard.recompensas', compact('recompensas', 'recompensasWithoutFirst', 'idNuevaRecompensa'));
    }
    
    public function store(Request $request) 
    {
        Recompensa::create($request->all());
        $messageStore = 'Recompensa guardada correctamente';
        return redirect()->route('recompensas.create')->with('successRecompensaStore', $messageStore);
    }

    public function update(Request $request) 
    {
        $recompensaSolicitada = Recompensa::find($request->idRecompensa);
        // Actualizar los campos
        $recompensaSolicitada->update([
            'costoPuntos_Recompensa' => $request->costoPuntos_Recompensa,
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
}
