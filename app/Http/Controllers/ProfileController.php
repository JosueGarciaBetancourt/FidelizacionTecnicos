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
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    public function verifyUserDataDuplication(Request $request) {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
        
            $userEmail = $request->input('userEmail', '');
            $DNI = $request->input('userDNI', '');
            $personalEmail = $request->input('userPersonalEmail', '');
            $personalPhone = $request->input('userPersonalPhone', '');
        
            // Inicializar el array de duplicaciones
            $duplicates = [];
        
            if (!empty($userEmail) && User::where('email', $userEmail)->exists()) {
                $duplicates['userEmail'] = true;
            }
        
            if (!empty($DNI) && User::where('DNI', $DNI)->exists()) {
                $duplicates['userDNI'] = true;
            }
        
            if (!empty($personalEmail) && User::where('correoPersonal', $personalEmail)->exists()) {
                $duplicates['userPersonalEmail'] = true;
            }
        
            if (!empty($personalPhone) && User::where('celularPersonal', $personalPhone)->exists()) {
                $duplicates['userPersonalPhone'] = true;
            }
            
            return response()->json([
                'success' => true,
                'user' => Auth::user(),
                'duplicates' => $duplicates,
            ]);
        } catch (\Throwable $error) {
            Log::error('Error en verifyUserDataDuplication: ', ['error' => $error]);
            return response()->json([
                'success' => false,
            ]);
        }
    }

    public function verifyUserEditDataDuplication(Request $request) {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no autenticado'
                ], 401);
            }
            
            $userID = $request->input('userID', '');
            
            // Validar si el usuario a editar existe
            $userToEdit = User::find($userID);
            if (!$userToEdit) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }
    
            $userEmail = $request->input('userEmail', '');
            $DNI = $request->input('userDNI', '');
            $personalEmail = $request->input('userPersonalEmail', '');
            $personalPhone = $request->input('userPersonalPhone', '');
    
            // Inicializar el array de duplicaciones
            $duplicates = [];
            
            if (!empty($userEmail) && User::where('email', $userEmail)->where('id', '!=', $userID)->exists()) {
                $duplicates['userEmail'] = true;
            }
        
            if (!empty($DNI) && User::where('DNI', $DNI)->where('id', '!=', $userID)->exists()) {
                $duplicates['userDNI'] = true;
            }
        
            if (!empty($personalEmail) && User::where('correoPersonal', $personalEmail)->where('id', '!=', $userID)->exists()) {
                $duplicates['userPersonalEmail'] = true;
            }
        
            if (!empty($personalPhone) && User::where('celularPersonal', $personalPhone)->where('id', '!=', $userID)->exists()) {
                $duplicates['userPersonalPhone'] = true;
            }
            
            return response()->json([
                'success' => true,
                'user' => $userToEdit,
                'duplicates' => $duplicates,
            ]);
        } catch (\Throwable $error) {
            Log::error('Error en verifyUserDataDuplication: ', ['error' => $error]);
            return response()->json([
                'success' => false,
            ]);
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
                /* ->select('id', 'idPerfilUsuario', 'name', 'email', 'DNI', 'surname', 'fechaNacimiento', 
                        'correoPersonal', 'celularPersonal', 'celularCorporativo', 'deleted_at') */
                ->get();

        // Para depurar nombre_PerfilUsuario
        //dd($users->pluck('nombre_PerfilUsuario'));

        $nombresPerfilesUsuariosNoAdmin = array_slice($this->returnArrayNombresPerfilesUsuarios(), 1);

        $perfilesUsuarios = PerfilUsuario::all()->pluck('nombre_PerfilUsuario', 'idPerfilUsuario');

        return view('dashboard.profileOwn', compact('users', 'nombresPerfilesUsuariosNoAdmin', 'perfilesUsuarios'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            // Obtener el usuario a partir del ID
            $user = User::find($request->id);
            
            // Verificar si el usuario existe
            if (!$user) {
                return Redirect::back()->withErrors(['update_error' => 'El usuario no existe.']);
            }
            
            // Validar y obtener los datos del request
            $data = $request->validated();

            // Si el campo password no está vacío, hashearlo antes de actualizar
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']); // No actualizar la contraseña si no se envía
            }
    
            // Si el email ha cambiado, invalidar la verificación de correo
            if (isset($data['email']) && $user->email !== $data['email']) {
                $user->email_verified_at = null;
            }

            // Rellenar y guardar los cambios
            $user->fill($data);
            $user->save();
    
            // Redirigir con un mensaje de éxito
            return Redirect::route('usuarios.create')->with('successUsuarioUpdate', 'Usuario actualizado correctamente');
        } catch (\Throwable $error) {
            // Registrar el error en los logs
            Log::error('Error actualizando el perfil: ', ['error' => $error]);
            dd('Error actualizando el perfil ', ['error' => $error]);

            // Redirigir con mensaje de error
            return Redirect::back()->withErrors(['update_error' => 'Ocurrió un error al actualizar el perfil. Inténtalo de nuevo.']);
        } catch (ValidationException $e) {
            dd("Errores de validación", $e->errors()); // Muestra los errores directamente
        }
    }

    public function enableUser($idUsuario)
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

    public function disableUser($idUsuario)
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

    public function deleteUser($idUsuario)
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
