<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Oficio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "Oficios";
    
    protected $primaryKey = 'idOficio';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idOficio',
        'nombre_Oficio',
        'descripcion_Oficio',
    ];

    protected $appends = ['codigoOficio', 'codigoNombreOficio']; // Agregar aquí el atributo dinámico

    public function tecnicosOficios() {
        return $this->hasMany(TecnicoOficio::class, 'idOficio', 'idOficio');
    }

    public function oficios() {
        return $this->belongsToMany(Tecnico::class, 'TecnicosOficios', 'idOficio', 'idTecnico');
    }

    public function getCodigoOficioAttribute() { //get{NombreDelCampo}Attribute
        return 'OFI-' . str_pad($this->idOficio, 2, '0', STR_PAD_LEFT);
    }

    public function getCodigoNombreOficioAttribute() {
        $codigoNombreOficio = 'OFI-' . str_pad($this->idOficio, 2, '0', STR_PAD_LEFT) . " | " . $this->nombre_Oficio;
        return $codigoNombreOficio;
    }
}
