<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudesCanje extends Model
{
    use HasFactory;

    protected $table = 'SolicitudesCanjes';
    protected $primaryKey = 'idSolicitudCanje';
    public $incrementing = false; // El ID no es autoincremental

    protected $fillable = [
        'idSolicitudCanje',
        'idVentaIntermediada',
        'fechaHoraEmision_VentaIntermediada',
        'idTecnico',
        'idEstadoSolicitudCanje',
        'idUser',
        'fechaHora_SolicitudCanje',
        'diasTranscurridos_SolicitudCanje',
        'puntosComprobante_SolicitudCanje',
        'puntosCanjeados_SolicitudCanje',
        'puntosRestantes_SolicitudCanje',
        'comentario_SolicitudCanje',
    ];

    protected $appends = ['userName', 'nombreEstado', 'recompensasJSON'];
    
    public function user() {
        return $this->belongsTo('App\Models\User');
    }
    
    public function estadosSolicitudCanje()
    {
        return $this->belongsTo(EstadosSolicitudCanje::class, 'idEstadoSolicitudCanje', 'idEstadoSolicitudCanje');
    }

    public function tecnicos()
    {
        return $this->belongsTo(Tecnico::class, 'idTecnico', 'idTecnico');
    }

    public function ventasIntermediadas()
    {
        return $this->belongsTo(VentaIntermediada::class, 'idVentaIntermediada', 'idVentaIntermediada');
    }

    // Relación con SolicitudCanjeRecompensas
    public function solicitudCanjeRecompensa()
    {
        return $this->hasMany(SolicitudCanjeRecompensa::class, 'idSolicitudCanje', 'idSolicitudCanje');
    }

    public function getUserNameAttribute()
    {
        $user = User::find($this->idUser);

        return $user ? $user->name : null;
    }

    public function getNombreEstadoAttribute()
    {
        $estadoSolicitudCanje = EstadosSolicitudCanje::find($this->idEstadoSolicitudCanje);
        
        return $estadoSolicitudCanje ? $estadoSolicitudCanje->nombre_EstadoSolicitudCanje : null;
    }

    public function getRecompensasJSONAttribute() {
        $recompensasJSON = DB::table('solicitudCanje_recompensas_view')
                ->where('idSolicitudCanje', $this->idSolicitudCanje)
                ->get();

        return $recompensasJSON;
    }
}
