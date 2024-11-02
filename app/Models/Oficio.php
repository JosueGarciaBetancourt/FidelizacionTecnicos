<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Oficio extends Model
{
    use SoftDeletes;

    protected $table = "Oficios";
    
    protected $primaryKey = 'idOficio';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idOficio',
        'nombre_Oficio',
    ];

    public function tecnicosOficios() {
        return $this->hasMany(TecnicoOficio::class, 'idOficio', 'idOficio');
    }

    public function getCodigoOficioAttribute() {
        return 'OFI-' . str_pad($this->idOficio, 2, '0', STR_PAD_LEFT);
    }
}
