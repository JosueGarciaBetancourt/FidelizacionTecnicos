<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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
        'nombreTecnico',
        'idEstadoSolicitudCanje',
        'idUser',
        'userName',
        'fechaHora_SolicitudCanje',
        'diasTranscurridos_SolicitudCanje',
        'puntosComprobante_SolicitudCanje',
        'puntosActuales_SolicitudCanje',
        'puntosCanjeados_SolicitudCanje',
        'puntosRestantes_SolicitudCanje',
        'comentario_SolicitudCanje',
    ];

    protected $appends = [/* 'userName',  */'nombreEstado', 'recompensasJSON', 'diasTranscurridosVenta'];
    
    public function getDiasTranscurridosVentaAttribute() {
        $diasTranscurridos = Controller::returnDiasTranscurridosHastaHoy($this->fechaHoraEmision_VentaIntermediada);
        return $diasTranscurridos;
    }

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

    /* public function getUserNameAttribute()
    {
        $user = User::find($this->idUser);

        return $user ? $user->name : null;
    } */

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($solicitudCanje) {
            $solicitudCanje->created_at = Carbon::now()->addHours(5);
            $solicitudCanje->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($solicitudCanje) {
            $solicitudCanje->updated_at = Carbon::now()->addHours(5);
        });
    }
}
