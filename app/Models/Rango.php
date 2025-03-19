<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rango extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "Rangos";
    
    protected $primaryKey = 'idRango';
    
    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idRango',
        'nombre_Rango',
        'descripcion_Rango',
        'puntosMinimos_Rango',
    ];
    
    public function tecnicos() {
        return $this->hasMany(Tecnico::class, 'idRango', 'idRango');
    }
}
