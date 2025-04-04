<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class TipoRecompensa extends Model
{
    use SoftDeletes;
    
    protected $table = "TiposRecompensas";

    protected $primaryKey = 'idTipoRecompensa';
    
    public $incrementing = true;  

    protected $fillable = [
        'idTipoRecompensa',
        'nombre_TipoRecompensa',
        'descripcion_TipoRecompensa',
        'colorTexto_TipoRecompensa',
        'colorFondo_TipoRecompensa',
    ];

    // Mutator para aplicar el valor por defecto
    public function setDescripcionTipoRecompensaAttribute($value)
    {
        $this->attributes['descripcion_TipoRecompensa'] = $value ?? 'Sin descripción';
    }
    
    protected $appends = ['codigoTipoRecompensa']; 

    public function recompensas()
    {
        return $this->hasMany(Recompensa::class, 'idTipoRecompensa', 'idTipoRecompensa'); 
    }

    public function getCodigoTipoRecompensaAttribute() {
        return 'TIPO-' . str_pad($this->idTipoRecompensa, 2, '0', STR_PAD_LEFT);
    }
}
