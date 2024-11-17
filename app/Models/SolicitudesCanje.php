<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudesCanje extends Model
{
    use HasFactory;

    protected $table = 'SolicitudesCanje';
    protected $primaryKey = 'idSolicitudCanje';
    public $incrementing = false; // El ID no es autoincremental

    protected $fillable = [
        'idSolicitudCanje',
        'idVentaIntermediada',
        'idTecnico',
        'idEstadoCanje',
        'fechaSolicitud',
    ];

    // Relación con EstadosCanje
    public function estado()
    {
        return $this->belongsTo(EstadosCanje::class, 'idEstadoCanje', 'idEstadoCanje');
    }

    // Relación con SolicitudCanjeRecompensas
    public function recompensas()
    {
        return $this->hasMany(SolicitudCanjeRecompensas::class, 'idSolicitudCanje', 'idSolicitudCanje');
    }
}
