<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCanjeRecompensas extends Model
{
    use HasFactory;

    protected $table = 'SolicitudCanjeRecompensas';

    protected $fillable = [
        'idCanje',
        'idRecompensa',
        'cantidad',
        'costoRecompensa',
    ];

    public function canje()
    {
        return $this->belongsTo(SolicitudCanje::class, 'idCanje', 'idSolicitudCanje');
    }

    public function recompensa()
    {
        return $this->belongsTo(Recompensa::class, 'idRecompensa', 'idRecompensa');
    }
}
