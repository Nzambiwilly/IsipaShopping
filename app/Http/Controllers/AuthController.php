<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nom_complet' => ['required', 'string', 'min:3', 'max:160'],
            'email' => ['required', 'email', 'max:120', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $user = User::create([
            'nom_complet' => $validated['nom_complet'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => 'client',
            'permission' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('catalogue')
            ->with('success', 'Inscription reussie. Bienvenue sur ISIPA Shopping.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, (bool) $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Identifiants invalides.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('catalogue'))
            ->with('success', 'Connexion reussie.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('catalogue')->with('success', 'Vous etes deconnecte.');
    }
}

