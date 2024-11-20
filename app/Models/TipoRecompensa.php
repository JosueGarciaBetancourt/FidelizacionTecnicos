<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoRecompensa extends Model
{
    use SoftDeletes;
    
    protected $table = "TiposRecompensas";

    protected $primaryKey = 'idTipoRecompensa';
    
    public $incrementing = true;  

    protected $fillable = [
        'idTipoRecompensa',
        'nombre_TipoRecompensa',
    ];

    public function recompensas()
    {
        return $this->hasMany(Recompensa::class, 'idTipoRecompensa', 'idTipoRecompensa'); 
    }

    public function getCodigoTipoRecompensaAttribute() { //get{NombreDelCampo}Attribute
        return 'TIPO-' . str_pad($this->idTipoRecompensa, 2, '0', STR_PAD_LEFT);
    }
}
