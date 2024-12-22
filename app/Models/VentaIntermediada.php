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

    protected $appends = ['fechaVenta', 'horaVenta', 'fechaCargada', 'horaCargada'];
  
    public function getFechaVentaAttribute() { 
        $fechaHoraCompleta = $this->fechaHoraEmision_VentaIntermediada;
        $carbonDate = Carbon::parse($fechaHoraCompleta);
        return $carbonDate->format('Y-m-d');
    }

    public function getHoraVentaAttribute() {
        $fechaHoraCompleta = $this->fechaHoraEmision_VentaIntermediada;
        $carbonDate = Carbon::parse($fechaHoraCompleta);
        return $carbonDate->format('H:i:s'); // Devuelve solo la hora
    }
    
    public function getFechaCargadaAttribute() {
        $fechaHoraCompleta = $this->fechaHoraCargada_VentaIntermediada;
    
        // Convertir a instancia de Carbon
        $carbonDate = Carbon::parse($fechaHoraCompleta);
    
        // Obtener solo la hora en formato deseado
        return $carbonDate->format('Y-m-d'); // Devuelve solo la hora
    }

    public function getHoraCargadaAttribute() {
        $fechaHoraCompleta = $this->fechaHoraCargada_VentaIntermediada;
    
        // Convertir a instancia de Carbon
        $carbonDate = Carbon::parse($fechaHoraCompleta);
    
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
