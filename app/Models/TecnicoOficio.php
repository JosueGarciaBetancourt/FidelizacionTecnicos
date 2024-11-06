<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TecnicoOficio extends Model
{
    use HasFactory;

    protected $table = 'TecnicosOficios';

    // Desactivar la clave primaria incremental, ya que es una clave compuesta
    public $incrementing = false;
    
    // Definir el tipo de las claves como 'string' para evitar el manejo autoincremental
    protected $keyType = 'string';

    // Especificar los campos que pueden asignarse masivamente
    protected $fillable = [
        'idTecnico',
        'idOficio',
    ];

    /**
     * Relación con el modelo Tecnico
     */
    public function tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'idTecnico', 'idTecnico');
    }

    /**
     * Relación con el modelo Oficio
     */
    public function oficio()
    {
        return $this->belongsTo(Oficio::class, 'idOficio', 'idOficio');
    }
}
