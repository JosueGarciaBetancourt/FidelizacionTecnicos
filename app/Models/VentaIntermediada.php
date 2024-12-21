<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaIntermediada extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'VentasIntermediadas';

    protected $primaryKey = 'idVentaIntermediada';

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idVentaIntermediada',
        'idTecnico',
        'nombreTecnico',
        'tipoCodigoCliente_VentaIntermediada',
        'codigoCliente_VentaIntermediada',
        'nombreCliente_VentaIntermediada',
        'fechaHoraEmision_VentaIntermediada',
        'fechaHoraCargada_VentaIntermediada',
        'montoTotal_VentaIntermediada',
        'puntosGanados_VentaIntermediada',
        'puntosActuales_VentaIntermediada',
        'idEstadoVenta',
    ];

    protected $appends = ['fechaVenta', 'horaVenta'];
  
    public function getFechaVentaAttribute() { 
        $fechaCompleta = $this->fechaHoraEmision_VentaIntermediada;
    
        // Convertir a instancia de Carbon
        $carbonDate = Carbon::parse($fechaCompleta);
    
        // Obtener solo la fecha en formato deseado
        return $carbonDate->format('Y-m-d');
    }

    public function getHoraVentaAttribute() {
        $fechaCompleta = $this->fechaHoraEmision_VentaIntermediada;
    
        // Convertir a instancia de Carbon
        $carbonDate = Carbon::parse($fechaCompleta);
    
        // Obtener solo la hora en formato deseado
        return $carbonDate->format('H:i:s'); // Devuelve solo la hora
    }

    // Relación uno a muchos (inversa)
    public function tecnico() {
        return $this->belongsTo(Tecnico::class, 'idTecnico', 'idTecnico');
    }

    // Relación uno a muchos (inversa)
    public function estadoVenta() {
        return $this->belongsTo(EstadoVenta::class, 'idEstadoVenta', 'idEstadoVenta');
    }

    // Relación uno a muchos
    public function canjes()
    {
        return $this->hasMany(Canje::class, 'idVentaIntermediada', 'idVentaIntermediada'); 
    }

    public function solicitudesCanje()
    {
        return $this->hasMany(SolicitudesCanje::class, 'idVentaIntermediada', 'idVentaIntermediada'); 
    }
}
