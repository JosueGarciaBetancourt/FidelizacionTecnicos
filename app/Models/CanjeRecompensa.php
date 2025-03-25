<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class CanjeRecompensa extends Model
{
    use HasFactory;

    protected $table = 'CanjesRecompensas';

    public $incrementing = false;
    
    protected $keyType = 'string';

    protected $fillable = [
        'idCanje',
        'idRecompensa',
        'cantidad',
        'costoRecompensa',
        'comentario',
    ];

    public function canje()
    {
        return $this->belongsTo(Canje::class, 'idCanje', 'idCanje');
    }

    public function recompensa()
    {
        return $this->belongsTo(Recompensa::class, 'idRecompensa', 'idRecompensa');
    }
}
