<?php

namespace App\Http\Controllers\Auth;

use App\Models\Setting;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {   
        $adminUsername = config('settings.adminUsername');
        $userEmailDomain = config('settings.emailDomain');
        $adminEmail = config('settings.adminEmail');

        return view('auth.login', compact('adminUsername', 'userEmailDomain', 'adminEmail'));
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('ventasIntermediadas.create', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->intended(route('login', absolute: false));
        //return redirect('/');
    }
}
