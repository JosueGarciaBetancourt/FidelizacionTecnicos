<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class PerfilUsuario extends Model
{
    use SoftDeletes;

    protected $table = "PerfilesUsuarios";
    
    protected $primaryKey = 'idPerfilUsuario';

    public $incrementing = true;  // Indica que la clave primaria es auto-incrementable

    protected $fillable = [
        'idPerfilUsuario',
        'nombre_PerfilUsuario',
        'created_at',
        'updated_at',
    ];

    // Mutador para created_at
    protected static function boot()
    {
        parent::boot();

        // Ajuste de hora al crear un registro
        static::creating(function ($perfilUsuario) {
            $perfilUsuario->created_at = Carbon::now()->addHours(5);
            $perfilUsuario->updated_at = Carbon::now()->addHours(5);
        });

        // Ajuste de hora al actualizar un registro
        static::updating(function ($perfilUsuario) {
            $perfilUsuario->updated_at = Carbon::now()->addHours(5);
        });
    }
      
    public function users()
    {
        return $this->hasMany(User::class, 'idPerfilUsuario', 'id'); 
    }
}
