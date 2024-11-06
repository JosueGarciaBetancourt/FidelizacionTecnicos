<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tecnico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "Tecnicos";
    
    protected $primaryKey = 'idTecnico';

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idTecnico',
        'nombreTecnico',
        'celularTecnico',
        'oficioTecnico',
        'fechaNacimiento_Tecnico',
        'totalPuntosActuales_Tecnico',
        'historicoPuntos_Tecnico',
        'rangoTecnico',
    ];

    public function ventasIntermediadas() {
        return $this->hasMany(VentaIntermediada::class, 'idTecnico', 'idTecnico');
    }

    public function LoginTecnico() {
        return $this->hasOne(Login_Tecnico::class, 'idTecnico', 'idTecnico');
    }

    public function tecnicosOficios() {
        return $this->hasMany(TecnicoOficio::class, 'idTecnico', 'idTecnico');
    }

    public function oficios() {
        return $this->belongsToMany(Oficio::class, 'TecnicosOficios', 'idTecnico', 'idOficio');
    }
}
