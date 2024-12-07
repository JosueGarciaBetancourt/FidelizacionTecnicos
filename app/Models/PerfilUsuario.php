<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PerfilUsuario extends Model
{
    use SoftDeletes;

    protected $table = "PerfilesUsuarios";
    
    protected $primaryKey = 'idPerfilUsuario';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idPerfilUsuario',
        'nombre_PerfilUsuario',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'idPerfilUsuario', 'id'); 
    }
}
