<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Login_Tecnico extends Model
{
    use HasFactory, SoftDeletes;

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

        static::creating(function ($login_tecnico) {
            $login_tecnico->created_at = Carbon::now()->addHours(5);
            $login_tecnico->updated_at = Carbon::now()->addHours(5);
        });

        static::updating(function ($login_tecnico) {
            $login_tecnico->updated_at = Carbon::now()->addHours(5);
        });
    }
}
