<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

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
        'idRango',
    ];

    public function ventasIntermediadas() {
        return $this->hasMany(VentaIntermediada::class, 'idTecnico', 'idTecnico');
    }

    public function loginTecnicos() {
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

    public function rangos() {
        return $this->belongsTo(Rango::class, 'idRango', 'idRango');
    }

    protected $appends = ['idsOficioTecnico', 'idNameOficioTecnico', 'idNombreTecnico', 'nombre_Rango']; // Agregar los campos dinámicos aquí

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

    public function getIdNombreTecnicoAttribute() {
        $idTecnico = $this->idTecnico ?? ''; 
        $nombreTecnico = $this->nombreTecnico ?? '';
        return $idTecnico . " | " . $nombreTecnico;
    }

    // Método de acceso corregido
    public function getNombreRangoAttribute() {
        return $this->rangos?->nombre_Rango ?? Rango::where('idRango', 1)->value('nombre_Rango');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tecnico) {
            $tecnico->created_at = Carbon::now()->addHours(5);
            $tecnico->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($tecnico) {
            $tecnico->updated_at = Carbon::now()->addHours(5);
        });
    }
}
