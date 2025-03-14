<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TecnicoNotification extends Model
{
    use HasFactory;
    
    protected $table = 'tecnicos_notifications';

    protected $fillable = [
        'idTecnico', 'idVentaIntermediada', 'description', 'active'
    ];
    
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans(); // "Hace 2 min"
    }
}