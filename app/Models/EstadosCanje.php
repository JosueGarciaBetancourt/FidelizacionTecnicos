<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadosCanje extends Model
{
    use HasFactory;

    protected $table = 'EstadosCanje';
    protected $primaryKey = 'idEstadoCanje';

    protected $fillable = [
        'descripcion',
    ];

    // Relación con SolicitudesCanje
    public function solicitudesCanje()
    {
        return $this->hasMany(SolicitudesCanje::class, 'idEstadoCanje', 'idEstadoCanje');
    }
}
