<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Recompensa extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "Recompensas";

    protected $primaryKey = 'idRecompensa';

    public $incrementing = false;  // Indica que la clave primaria no es auto-incrementable

    protected $keyType = 'string';  // Indica que la clave primaria es de tipo string

    protected $fillable = [
        'idRecompensa',
        'tipoRecompensa',
        'descripcionRecompensa',
        'costoPuntos_Recompensa',
        'stock_Recompensa',
    ];

    public function canjesRecompensas()
    {
        return $this->hasMany(CanjeRecompensa::class, 'idRecompensa', 'idRecompensa'); 
    }
}
