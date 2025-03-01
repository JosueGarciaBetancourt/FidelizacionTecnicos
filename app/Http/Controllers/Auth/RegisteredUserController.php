<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    /*public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }*/
    
    public function store(Request $request): RedirectResponse
    {
        //dd($request->all());

        $userDeleted = User::onlyTrashed()->where('email', $request['email'])->first();

        if ($userDeleted) {
            $userDeleted->restore();
            return redirect(route('usuarios.create', absolute: false))->with('successUsuarioStore', 'Usuario guardado correctamente.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:7'],
            'idPerfilUsuario' => ['required', 'integer', 'exists:PerfilesUsuarios,idPerfilUsuario'], 
            'DNI' => ['nullable', 'string', 'size:8', 'unique:users,DNI'], 
            'personalName' => ['nullable', 'string'], 
            'surname' => ['nullable', 'string'], 
            'fechaNacimiento' => ['nullable', 'date'], 
            'correoPersonal' => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:users,correoPersonal'], 
            'celularPersonal' => ['nullable', 'string', 'regex:/^[0-9]{9}$/' , 'unique:users,celularPersonal'], 
            'celularCorporativo' => ['nullable', 'string', 'regex:/^[0-9]{9}$/'], 
        ]);
        

        // Crear nuevo usuario si no ha sido registrado anteriormente
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'idPerfilUsuario' => $request->idPerfilUsuario,
        ];
        
        // Agregar los campos opcionales solo si están presentes
        $optionalFields = ['DNI', 'personalName', 'surname', 'fechaNacimiento', 'correoPersonal', 'celularPersonal', 'celularCorporativo'];
        
        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $userData[$field] = $request->$field;
            }
        }
        
        // Crear el usuario con los datos requeridos y opcionales
        $user = User::create($userData);

        event(new Registered($user));
        
        return redirect(route('usuarios.create', absolute: false))->with('successUsuarioStore', 'Usuario guardado correctamente.');
    }
}
