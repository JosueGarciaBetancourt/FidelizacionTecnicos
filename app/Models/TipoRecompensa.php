<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoRecompensa extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $table = "TiposRecompensas";

    protected $primaryKey = 'idTipoRecompensa';

    public $incrementing = true;  

    protected $fillable = [
        'idTipoRecompensa',
        'nombre_TipoRecompensa',
    ];

    public function recompensas()
    {
        return $this->hasMany(Recompensa::class, 'idTipoRecompensa', 'idTipoRecompensa'); 
    }
}
