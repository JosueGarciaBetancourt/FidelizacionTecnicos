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

    public function solicitudesCanje() {
        return $this->hasMany(SolicitudesCanje::class, 'idTecnico', 'idTecnico');
    }

    public function oficios() {
        return $this->belongsToMany(Oficio::class, 'TecnicosOficios', 'idTecnico', 'idOficio')
                ->select('Oficios.idOficio', 'Oficios.nombre_Oficio'); // Especifica las columnas a seleccionar
    }

    protected $appends = ['idsOficioTecnico', 'idNameOficioTecnico']; // Agregar los campos dinámicos aquí

    // Método de acceso para obtener los IDs de los oficios asociados al técnico
    public function getIdsOficioTecnicoAttribute()
    {
        // Obtener todos los oficios asociados a este técnico
        $oficios = $this->oficios()->pluck('idOficio');

        // Retornar los IDs como un string en formato JSON o como se desee
        return $oficios->isNotEmpty() ? '[' . $oficios->implode(',') . ']' : 'No tiene oficios';
    }

    // Método de acceso para obtener los nombres de los oficios asociados al técnico
    public function getIdNameOficioTecnicoAttribute()
    {
        // Obtener los nombres de los oficios asociados a este técnico
        $oficios = $this->oficios()->pluck('idOficio', 'nombre_Oficio');

        // Retornar los nombres de los oficios como un string concatenado
        return $oficios->isNotEmpty() 
            ? $oficios->map(fn($id, $name) => "{$id}-{$name}")->implode(' | ') 
            : 'No tiene oficios';
    }
}
