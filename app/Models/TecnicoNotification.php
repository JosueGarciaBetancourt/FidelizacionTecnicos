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
        'idTecnico', 'idVentaIntermediada', 'idSolicitudCanje', 'description', 'active'
    ];
    
    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans(); // "Hace 2 min"
    }
}