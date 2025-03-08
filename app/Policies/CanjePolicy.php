<?php

namespace App\Policies;

use App\Models\User;

class CanjePolicy
{
    public function __construct()
    {
        //
    }

    public function registrar(User $user)
    {
        return ($user->idPerfilUsuario !== 3);
    }
}

