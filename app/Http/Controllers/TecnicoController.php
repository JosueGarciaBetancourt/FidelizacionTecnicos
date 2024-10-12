<?php

namespace App\Http\Controllers;

use App\Models\Tecnico;
use Illuminate\Http\Request;
use App\Models\Login_Tecnico;
use Yajra\DataTables\DataTables;

class TecnicoController extends Controller
{   
    public function create()
    {
        // Obtener todas las recompensas activas
        $tecnicos = Tecnico::all();
        
        return view('dashboard.tecnicos', compact('tecnicos'));
    }

    function store(Request $request) 
    {   
        $validatedDataTecnico = $request->validate([
            'idTecnico' => 'required|unique:Tecnicos|max:8',
            'nombreTecnico' => 'required|string|max:100',
            'celularTecnico' => 'required|max:9',
            'oficioTecnico' => 'required|string',
            'fechaNacimiento_Tecnico' => 'required',
        ]);
        
        // Guardar el técnico en la base de datos
        $tecnico = new Tecnico($validatedDataTecnico);
        $tecnico->save();

        
        $validatedDatLoginTecnico = $request->validate([
            'idTecnico' => 'required|unique:login_tecnicos|max:8',
        ]);
        
        // Guardar el técnico en la tabla login_tecnicos con la contraseña por defecto (DNI) que podrá ser cambiado desde la APP
        $login_tecnico = new Login_Tecnico([
            'idTecnico' => $validatedDatLoginTecnico['idTecnico'],
            'password' => $validatedDatLoginTecnico['idTecnico'],
        ]);

        $login_tecnico->save();

        // Obtener el origen de la solicitud
        $origin = $request->input('origin');

        // Redirigir basado en el origen
        switch ($origin) { 
            case 'ventasIntermediadas.create':
                return redirect()->route('ventasIntermediadas.create')->with('successTecnicoStore', 'Técnico agregado exitosamente desde ventas.');
            default:
                return redirect()->route('tecnicos.create')->with('successTecnicoStore', 'Técnico agregado exitosamente.');
        }
    }

    function update(Request $request) 
    {
        $tecnicoSolicitado = Tecnico::find($request->idTecnico);
        // Actualizar los campos
        $tecnicoSolicitado->update([
            'celularTecnico' => $request->celularTecnico,
            'oficioTecnico' => $request->oficioTecnico
        ]);
        $messageUpdate = 'Técnico actualizado correctamente';
        return redirect()->route('tecnicos.create')->with('successTecnicoUpdate', $messageUpdate);
    }

    public function delete(Request $request) 
    {
        // Encuentra la recompensa usando el idRecompensa
        $tecnico = Tecnico::where("idTecnico", $request->idTecnico)->first();
    
        // Verifica si se encontró la recompensa
        if ($tecnico) {
            // Aplica soft delete
            $tecnico->delete();
    
            $messageDelete = 'Técnico eliminado correctamente';
        } else {
            $messageDelete = 'Técnico no encontrado';
        }
    
        return redirect()->route('tecnicos.create')->with('successTecnicoDelete', $messageDelete);
    }

    public function tabla()
    {
        return DataTables::make(Tecnico::all())->toJson();
    }
}
