<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\PerfilUsuario;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
        abort_if(!Auth::check(), 403, 'Acceso denegado');
        
        $user = Auth::user();

        $users = User::with('PerfilUsuario')
            ->when($user->idPerfilUsuario !== 1, function($query) use ($user) {
                $query->where('id', $user->id);
            })
            ->select('id', 'idPerfilUsuario', 'name', 'email', 'DNI', 'surname', 'fechaNacimiento', 
                    'correoPersonal', 'celularPersonal', 'celularCorporativo')
            ->get();

        // Para depurar nombre_PerfilUsuario
        //dd($users->pluck('nombre_PerfilUsuario'));

        $nombresPerfilesUsuarios = $this->returnArrayNombresPerfilesUsuarios();
        $perfilesUsuarios = PerfilUsuario::all()->pluck('nombre_PerfilUsuario', 'idPerfilUsuario');

        return view('dashboard.profileOwn', compact('users', 'nombresPerfilesUsuarios', 'perfilesUsuarios'));
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
        try {
            // Obtener el usuario a partir del id
            $user = User::where('id', $request->id)->first();

            // Validar y actualizar los campos, incluyendo la lógica del password
            $data = $request->validated();
    
            // Si el campo password no está vacío, hashearlo antes de actualizar
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                // Eliminar la clave 'password' para no actualizarla
                unset($data['password']);
            }
    
            // Rellenar y guardar los cambios en el modelo
            $user->fill($data);
            
            // Si el campo 'email' ha cambiado, invalidar la verificación de correo
            /*if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }*/
            
            // Guardar los cambios
            $user->save();
           
            //dd($user); 
    
            // Redirigir con un mensaje de éxito
            return Redirect::route('usuarios.create')->with('status', '¡Perfil actualizado con éxito!');
        } catch (\Throwable $error) {
            // Registrar el error en los logs de la aplicación
            Log::error('Error actualizando el perfil: ', ['error' => $error]);
            // Redirigir con un mensaje de error
            return Redirect::back()->withErrors(['update_error' => 'Ocurrió un error al actualizar el perfil. Inténtalo de nuevo.']);
        }
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
