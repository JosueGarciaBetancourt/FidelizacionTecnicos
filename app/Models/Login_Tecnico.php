<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Login_Tecnico extends Model
{
    use HasFactory;

    protected $table = "login_tecnicos";
    
    protected $primaryKey = 'idTecnico';

    protected $keyType = 'string';

    protected $fillable = [
        'idTecnico',
        'password',
    ];
}
