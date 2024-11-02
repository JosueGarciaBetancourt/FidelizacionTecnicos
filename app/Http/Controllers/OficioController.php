<?php

namespace App\Http\Controllers;

use App\Models\Oficio;
use Illuminate\Http\Request;

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
        // Desde el modelo se agrega un campo dinámico codigoOficio (OFI-01)
        $oficios = Oficio::all(); 
        // Para depurar el códigoOficio
        /* foreach ($oficios as $oficio) {
            dd($oficio->codigoOficio); 
        }*/
        $nuevoCodigoOficio = $this->returnNuevoCodigoOficio();

        $oficiosEliminados = Oficio::onlyTrashed()->get();
 
        return view('dashboard.oficios', compact('oficios', 'nuevoCodigoOficio', 'oficiosEliminados'));
    }

    public function store() {

    }

    public function update() {
        
    }

    public function delete() {
        
    }

    public function restaurar() {
        
    }
}
