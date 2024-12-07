<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    public function create() {
        $user = Auth::user(); // Obtiene el usuario autenticado
        
        if (!$user) {
            abort(403, 'Acceso denegado'); // Detiene la ejecución si no hay usuario autenticado
        }
    
        if ($user->email == "admin@dimacof.com") {
            $users = User::all(); // Obtiene todos los usuarios si es admin
        } else {
            $users = [$user]; // Crea una colección con el usuario actual
        }
    
        return view('dashboard.profileOwn', compact('users'));
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
