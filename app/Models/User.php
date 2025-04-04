<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'DNI',
        'personalName',
        'surname',
        'fechaNacimiento',
        'correoPersonal',
        'celularPersonal',
        'celularCorporativo',
        'idPerfilUsuario',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $appends = ['nombre_PerfilUsuario'];

    public function PerfilUsuario() 
    {
        return $this->belongsTo(PerfilUsuario::class, 'idPerfilUsuario', 'idPerfilUsuario');
    }

    public function getNombrePerfilUsuarioAttribute()
    {
        return $this->PerfilUsuario->nombre_PerfilUsuario ?? 'Sin perfil';
    }
}
