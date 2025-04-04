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
