<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TecnicoNotification extends Model
{
    use HasFactory;
    
    protected $table = 'tecnicos_notifications';
    public $timestamps = true;

    protected $fillable = [
        'idTecnico', 'idVentaIntermediada', 'description', 'active'
    ];
    
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans(); // "Hace 2 min"
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tecnicoNotification) {
            $tecnicoNotification->created_at = Carbon::now()->addHours(5);
            $tecnicoNotification->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($tecnicoNotification) {
            $tecnicoNotification->updated_at = Carbon::now()->addHours(5);
        });
    }
}