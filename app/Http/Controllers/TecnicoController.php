<?php

namespace App\Http\Controllers;
use App\Models\Tecnico;
use Illuminate\Http\Request;
use App\Models\Login_Tecnico;
use Yajra\DataTables\DataTables;
use App\Models\VentaIntermediada;

class TecnicoController extends Controller
{   
    public function create()
    {
        // Obtener todas los técnicos activos
        $tecnicos = Tecnico::all();
       
        return view('dashboard.tecnicos', compact('tecnicos'));
    }

    function store(Request $request) 
    {   
        $validatedDataTecnico = $request->validate([
            'idTecnico' => 'required|max:8', // FALTA VALIDAR CORRECTAMENTE ESTE CAMPO PARA MAYOR SEGURIDAD
            'nombreTecnico' => 'required|string|max:100',
            'celularTecnico' => 'required|max:9',
            'oficioTecnico' => 'required|string',
            'fechaNacimiento_Tecnico' => 'required',
        ]);
        
        // Obtener el origen de la solicitud
        $origin = $request->input('origin'); // Con JS se modifica el valor del input en modalAgregarNuevoTecnico.blade.php

        // Comprobar si el técnico fue borrado con soft delete
        $tecnicoBorrado = Tecnico::onlyTrashed()->where('idTecnico', $validatedDataTecnico['idTecnico'])->first();

        if ($tecnicoBorrado) {
            // Restaurar el técnico si ha sido eliminado lógicamente
            $tecnicoBorrado->restore();
            $rango = $this->getRango($tecnicoBorrado->historicoPuntos_Tecnico);
            $tecnicoData = array_merge($validatedDataTecnico, ['rangoTecnico' => $rango]);
            $tecnicoBorrado->update($tecnicoData);
            $origin = "recontratado";
        } else {
            // Crear un nuevo técnico si no existe
            $tecnico = new Tecnico($validatedDataTecnico);
            $tecnico->save();
            
            // Login tecnico
            $validatedDatLoginTecnico = $request->validate([
                'idTecnico' => 'required|unique:login_tecnicos|max:8',
            ]);
            
            // Contraseña por defecto (DNI) que podrá ser cambiada desde la APP
            $login_tecnico = new Login_Tecnico([
                'idTecnico' => $validatedDatLoginTecnico['idTecnico'],
                'password' => bcrypt($validatedDatLoginTecnico['idTecnico']),
            ]);

            $login_tecnico->save();
        }

        // Redirigir basado en el origen
        switch ($origin) { 
            case 'ventasIntermediadas.create':
                return redirect()->route('ventasIntermediadas.create')->with('successTecnicoStore', 'Técnico agregado exitósamente desde ventas.');
            case 'recontratado':
                return redirect()->route('tecnicos.create')->with('successTecnicoRecontratadoStore', 'Técnico agregado exitósamente.');
            default:
                return redirect()->route('tecnicos.create')->with('successTecnicoStore', 'Técnico agregado exitósamente.');
        }
    }

    function update(Request $request) 
    {
        $tecnicoSolicitado = Tecnico::find($request->idTecnico);
        $rango = $this->getRango($tecnicoSolicitado->historicoPuntos_Tecnico);
        $tecnicoSolicitado->update([
            'celularTecnico' => $request->celularTecnico,
            'oficioTecnico' => $request->oficioTecnico,
            'rangoTecnico' => $rango,
        ]);

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

    public function tabla()
    {
        return DataTables::make(Tecnico::all())->toJson();
    }
}
