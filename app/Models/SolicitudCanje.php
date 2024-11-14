<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudCanje extends Model
{
    use HasFactory;

    protected $table = 'SolicitudesCanje';
    protected $primaryKey = 'idSolicitudCanje';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'idSolicitudCanje',
        'idVentaIntermediada',
        'idTecnico',
        'estado',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->idSolicitudCanje = 'SOLICANJ-' . str_pad(self::max('id') + 1, 5, '0', STR_PAD_LEFT);
        });
    }

    public function recompensas()
    {
        return $this->hasMany(SolicitudCanjeRecompensas::class, 'idCanje', 'idSolicitudCanje');
    }
}
