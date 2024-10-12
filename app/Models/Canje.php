<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canje extends Model
{
    use HasFactory;

    protected $table = 'Canjes';

    protected $primaryKey = ['idCanje', 'idRecompensa'];

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idCanje',
        'idRecompensa',
        'idVentaIntermediada',
        'fechaHora_Canje',
        'cantidadRecompensa_Canje',
        'totalPuntosCanjeados_Canje',
        'diasTranscurridos_Canje',
        'rutaPDF_Canje',
        'idUser',
    ];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function recompensa() {
        return $this->belongsTo(Recompensa::class, 'idRecompensa', 'idRecompensa');
    }

    public function ventasIntermediadas()
    {
        return $this->belongsTo(VentaIntermediada::class, 'idVentaIntermediada'); 
    }
}
