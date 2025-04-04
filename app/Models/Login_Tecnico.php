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
}
