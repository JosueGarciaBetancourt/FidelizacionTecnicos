<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\PerfilUsuario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{    
    public function returnArrayNombresPerfilesUsuarios() {
        $perfiles = PerfilUsuario::all();
        $arrayNombresPerfilesUsuarios = [];

        // Obtener todos los nombres de los perfiles 
        foreach ($perfiles as $perfil) {
            $arrayNombresPerfilesUsuarios[] = $perfil->nombre_PerfilUsuario;
        }

        return $arrayNombresPerfilesUsuarios;
    }

    public function returnCurrentPerfilUsuario($idPerfilUsuario) {
        return PerfilUsuario::where('idPerfilUsuario', $idPerfilUsuario)
                            ->value('nombre_PerfilUsuario');
    }

    public function create() 
    {
        // Verifica que haya un usuario autenticado, de lo contrario, lanza un error 403
        abort_if(!Auth::check(), 403, 'Acceso denegado');
    
        // Obtiene el usuario autenticado
        $user = Auth::user();
        $currentPerfil = $this->returnCurrentPerfilUsuario($user->idPerfilUsuario);
        
        // Consulta para obtener usuarios con sus perfiles
        $usersQuery = User::with([
            'PerfilUsuario' => function($query) {
                $query->select('idPerfilUsuario', 'nombre_PerfilUsuario'); // Relación con PerfilUsuario
            }
        ]);
    
        // Si no es administrador, se filtra por su propio ID 
        if ($user->email !== "admin@dimacof.com") {
            $usersQuery->where('id', $user->id);
        }
    
        // Se obtienen los usuarios (solo seleccionando las columnas de la tabla users)
        $users = $usersQuery->select('id', 'name', 'email', 'DNI', 'surname', 'fechaNacimiento', 
                                     'correoPersonal', 'celularPersonal', 'celularCorporativo')->get();
        
        // Aquí ya puedes acceder a la relación como $user->PerfilUsuario->nombre_PerfilUsuario
        foreach ($users as $user) {
            $user->nombre_PerfilUsuario = $user->PerfilUsuario->nombre_PerfilUsuario ?? 'Sin perfil';
        }
    
        // Obtiene los nombres de los perfiles de usuarios
        $nombresPerfilesUsuarios = $this->returnArrayNombresPerfilesUsuarios();
    
        // Retorna la vista con los usuarios y los nombres de los perfiles de usuarios
        return view('dashboard.profileOwn', compact('users', 'currentPerfil', 'nombresPerfilesUsuarios'));
    }

    /*public function edit(Request $request): View
    {
        return view('modals.profile.modalEditProfile', [
            'user' => $request->user(),
        ]);

        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }*/

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
