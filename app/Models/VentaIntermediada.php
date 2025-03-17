<?php

namespace App\Models;

use Carbon\Carbon;
use App\Http\Controllers\Controller;
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
        'apareceEnSolicitud',
    ];

    protected $appends = ['tipoComprobante', 'diasTranscurridos', 'fechaVenta', 'horaVenta', 'fechaCargada', 'horaCargada'];
  
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

    public function getTipoComprobanteAttribute() {
        $tipoComprobante = '';

        if (strpos($this->idVentaIntermediada, 'F') !== false) {
            $tipoComprobante = 'FACTURA ELECTRÓNICA';
        } elseif (strpos($this->idVentaIntermediada, 'B') !== false) {
            $tipoComprobante = 'BOLETA DE VENTA ELECTRÓNICA';
        }

        return $tipoComprobante;
    }
    
    public function getDiasTranscurridosAttribute() {
        $diasTranscurridos = Controller::returnDiasTranscurridosHastaHoy($this->fechaHoraEmision_VentaIntermediada);
        return $diasTranscurridos;
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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ventaIntermediada) {
            $ventaIntermediada->created_at = Carbon::now()->addHours(5);
            $ventaIntermediada->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($ventaIntermediada) {
            $ventaIntermediada->updated_at = Carbon::now()->addHours(5);
        });
    }
}
