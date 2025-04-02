<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Canje extends Model
{
    use HasFactory;

    protected $table = 'Canjes';

    protected $primaryKey = 'idCanje';

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idCanje',
        'idVentaIntermediada',
        'fechaHoraEmision_VentaIntermediada',
        'fechaHora_Canje',
        'diasTranscurridos_Canje',
        'puntosComprobante_Canje',
        'puntosActuales_Canje',
        'puntosCanjeados_Canje',
        'puntosRestantes_Canje',
        'recompensas_Canje',
        'comentario_Canje',
        'idUser',
    ];

    protected $appends = ['recompensasJSON']; 

    public function getRecompensasJSONAttribute() {
        $recompensasJSON = DB::table('canje_recompensas_view')
            ->where('idCanje', $this->idCanje)
            ->get();

        return $recompensasJSON;
    }

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function ventasIntermediadas()
    {
        return $this->belongsTo(VentaIntermediada::class, 'idVentaIntermediada'); 
    }

    public function canjesRecompensas()
    {
        return $this->hasMany(CanjeRecompensa::class, 'idCanje', 'idCanje'); 
    }
}
