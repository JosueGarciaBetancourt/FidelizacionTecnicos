<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EstadosSolicitudCanje extends Model
{
    use SoftDeletes;

    protected $table = "EstadosSolicitudesCanjes";
    
    protected $primaryKey = 'idEstadoSolicitudCanje';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idEstadoSolicitudCanje',
        'nombre_EstadoSolicitudCanje',
    ];

    public function solicitudesCanje() {
        return $this->hasMany(SolicitudesCanje::class, 'idEstadoSolicitudCanje', 'idEstadoSolicitudCanje');
    }
}
