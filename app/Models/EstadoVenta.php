<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class EstadoVenta extends Model
{
    use SoftDeletes;

    protected $table = "EstadoVentas";
    
    protected $primaryKey = 'idEstadoVenta';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idEstadoVenta',
        'nombre_EstadoVenta',
    ];

    public function solicitudesCanjes() {
        return $this->hasMany(SolicitudesCanje::class, 'idEstadoVenta', 'idEstadoVentas');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($estadoVenta) {
            $estadoVenta->created_at = Carbon::now()->addHours(5);
            $estadoVenta->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($estadoVenta) {
            $estadoVenta->updated_at = Carbon::now()->addHours(5);
        });
    }
}

