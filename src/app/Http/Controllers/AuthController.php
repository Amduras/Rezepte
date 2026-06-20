<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('username', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors(['username' => 'Die Kombination aus Benutzername und Passwort ist ungültig.']);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('home'))
            ->with('success', 'Willkommen zurück, ' . Auth::user()->username . '!');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $user = User::create([
            'username'      => $request->input('username'),
            'email'         => $request->input('email'),
            'password_hash' => Hash::make($request->input('password')),
            'role'          => 'user',
            'status'        => 'active',
        ]);

        Auth::login($user);

        return redirect()->route('home')
            ->with('success', 'Konto erstellt! Willkommen, ' . $user->username . '!');
    }

    public function logout(LoginRequest $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Erfolgreich abgemeldet.');
    }
}
