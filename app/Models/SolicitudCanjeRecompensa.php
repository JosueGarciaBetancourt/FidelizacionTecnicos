<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCanjeRecompensa extends Model
{
    use HasFactory;

    protected $table = 'SolicitudesCanjesRecompensas';

    protected $fillable = [
        'idSolicitudCanje',
        'idRecompensa',
        'cantidad',
        'costoRecompensa',
    ];

    public function solicitudCanje()
    {
        return $this->belongsTo(SolicitudesCanje::class, 'idSolicitudCanje', 'idSolicitudCanje');
    }

    public function recompensas()
    {
        return $this->belongsTo(Recompensa::class, 'idRecompensa', 'idRecompensa');
    }
}
