<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Recompensa extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "Recompensas";

    protected $primaryKey = 'idRecompensa';

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idRecompensa',
        'idTipoRecompensa',
        'descripcionRecompensa',
        'costoPuntos_Recompensa',
        'stock_Recompensa',
    ];

    public function canjesRecompensas()
    {
        return $this->hasMany(CanjeRecompensa::class, 'idRecompensa', 'idRecompensa'); 
    }

    public function solicitudesCanjesRecompensas()
    {
        return $this->hasMany(SolicitudCanjeRecompensa::class, 'idRecompensa', 'idRecompensa'); 
    }

    public function tipoRecompensa()
    {
        return $this->belongsTo(TipoRecompensa::class, 'idTipoRecompensa', 'idTipoRecompensa');
    }

    protected $appends = ['nombre_TipoRecompensa'];

    public function getNombreTipoRecompensaAttribute()
    {
        return $this->tipoRecompensa ? $this->tipoRecompensa->nombre_TipoRecompensa : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($recompensa) {
            $recompensa->created_at = Carbon::now()->addHours(5);
            $recompensa->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($recompensa) {
            $recompensa->updated_at = Carbon::now()->addHours(5);
        });
    }
}
