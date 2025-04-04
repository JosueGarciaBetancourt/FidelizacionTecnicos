<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

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
        'colorTexto_Rango',
        'colorFondo_Rango',
    ];
    
    // Mutator para aplicar el valor por defecto
    public function setDescripcionRangoAttribute($value)
    {
        $this->attributes['descripcion_Rango'] = $value ?? 'Sin descripción';
    }

    public function setColorTextoRangoAttribute($value)
    {
        $this->attributes['colorTexto_Rango'] = $value ?? '#3206B0';
    }

    public function setColorFondoRangoAttribute($value)
    {
        $this->attributes['colorFondo_Rango'] = $value ?? '#DCD5F0';
    }

    public function tecnicos() {
        return $this->hasMany(Tecnico::class, 'idRango', 'idRango');
    }

    protected $appends = ['codigoRango']; 

    public function getCodigoRangoAttribute() { 
        return 'RAN-' . str_pad($this->idRango, 2, '0', STR_PAD_LEFT);
    }
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($rango) {
            $rango->created_at = Carbon::now()->addHours(5);
            $rango->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($rango) {
            $rango->updated_at = Carbon::now()->addHours(5);
        });
    }
}
