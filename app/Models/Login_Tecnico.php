<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Login_Tecnico extends Model
{
    use HasFactory;

    protected $table = "login_tecnicos";
    
    protected $primaryKey = 'idTecnico';

    protected $keyType = 'string';

    protected $fillable = [
        'idTecnico',
        'password',
        'isFirstLogin',
    ];

    public function Tecnico()
    {
        return $this->belongsTo(Tecnico::class, 'idTecnico', 'idTecnico');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($loginTecnico) {
            $loginTecnico->created_at = Carbon::now()->addHours(5);
            $loginTecnico->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($loginTecnico) {
            $loginTecnico->updated_at = Carbon::now()->addHours(5);
        });
    }
}
