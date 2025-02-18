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
    public function getLoggedUser() { 
        if (Auth::check() && Auth::user()) {
            return response()->json([
                'success' => true,
                'user' => Auth::user(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No autenticado'
            ], 401); // Código 401 para indicar que no está autenticado
        }
    }

    public function returnArrayNombresPerfilesUsuarios() {
        // Obtiene todos los perfiles de usuario
        $perfiles = PerfilUsuario::all();

        // Verifica si hay un usuario autenticado y si es el administrador
        if (Auth::check() && Auth::user()->email !== env('ADMIN_EMAIL', "admin@dimacof.com")) {
            // Rechazar (eliminar) los perfiles donde idPerfilUsuario sea 1
            $perfiles = $perfiles->reject(function($perfil) {
                return $perfil->idPerfilUsuario === 1;
            });
        }

        $arrayNombresPerfilesUsuarios = [];

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
                ->withTrashed() // Incluye registros con soft delete
                ->select('id', 'idPerfilUsuario', 'name', 'email', 'DNI', 'surname', 'fechaNacimiento', 
                        'correoPersonal', 'celularPersonal', 'celularCorporativo', 'deleted_at')
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
            return Redirect::route('usuarios.create')->with('successUsuarioUpdate', 'Usuario actualizado correctamente');
        } catch (\Throwable $error) {
            // Registrar el error en los logs de la aplicación
            Log::error('Error actualizando el perfil: ', ['error' => $error]);
            // Redirigir con un mensaje de error
            return Redirect::back()->withErrors(['update_error' => 'Ocurrió un error al actualizar el perfil. Inténtalo de nuevo.']);
        }
    }

    public function enable($idUsuario)
    {
        // Verificar si el usuario existe 
        $user = User::onlyTrashed()->find($idUsuario);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        $user->restore();

        return response()->json([
            'message' => 'Usuario ' . $user->name . ' habilitado correctamente.',
        ], 200);
    }

    public function disable($idUsuario)
    {
        // Verificar si el usuario existe
        $user = User::find($idUsuario);

        if (!$user) {
            return response()->json([
                'message' => 'Usuario no encontrado.'
            ], 404);
        }

        // Soft delete
        $user->delete();

        return response()->json([
            'message' => 'Usuario ' . $user->name . ' inhabilitado correctamente.',
        ], 200);
    }

    public function delete($idUsuario)
    {
        // Verificar si el usuario existe
        $user = User::find($idUsuario);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'El usuario no fue encontrado.',
                'details' => 'El ID proporcionado no corresponde a ningún usuario en la base de datos.',
                'error_code' => 404, // Código de error consistente
            ], 404);
        }

        try {
            // Eliminar el usuario de forma permanente
            $user->forceDelete();

            return response()->json([
                'status' => 'success',
                'message' => "El usuario {$user->name} fue eliminado correctamente.",
                'data' => [
                    'user_id' => $idUsuario
                ]
            ], 200);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1]; // El código de error SQL específico

            if ($errorCode == 1451) { // Error 1451: Restricción de clave foránea
                return response()->json([
                    'status' => 'error', 
                    'message' => "No se pudo eliminar el usuario {$user->name}",
                    'details' => 'El usuario tiene registros asociados en otras tablas, como canjes o solicitudes de canje',
                    'error_code' => 1451
                ], 400);
            }

            return response()->json([
                'status' => 'error',
                'message' => 'Ocurrió un error inesperado al intentar eliminar el usuario.',
                'details' => 'Excepción de base de datos no controlada.',
                'error_code' => 500,
                'technical_message' => config('app.debug') ? $e->getMessage() : 'Error interno, contacte al administrador.'
            ], 500);
        }
    }
}
