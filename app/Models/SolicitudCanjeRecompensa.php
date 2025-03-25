<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitudCanjeRecompensa) {
            $solicitudCanjeRecompensa->created_at = Carbon::now()->addHours(5);
            $solicitudCanjeRecompensa->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($solicitudCanjeRecompensa) {
            $solicitudCanjeRecompensa->updated_at = Carbon::now()->addHours(5);
        });
    }
}
