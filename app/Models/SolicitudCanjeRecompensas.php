<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCanjeRecompensas extends Model
{
    use HasFactory;

    protected $table = 'SolicitudCanjeRecompensas';

    protected $fillable = [
        'idSolicitudCanje',
        'idRecompensa',
        'cantidad',
        'costoRecompensa',
    ];

    // Relación con SolicitudesCanje
    public function solicitud()
    {
        return $this->belongsTo(SolicitudesCanje::class, 'idSolicitudCanje', 'idSolicitudCanje');
    }
}
