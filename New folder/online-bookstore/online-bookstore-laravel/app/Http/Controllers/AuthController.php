<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $creds = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Signed in.');
        }

        return redirect()->route('login')->withErrors(['email' => 'Incorrect email or password.'])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $payload = $request->validate([
            'name'     => 'required|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'name.required'     => 'Name must be at least 3 characters.',
            'name.min'          => 'Name must be at least 3 characters.',
            'email.required'    => 'Enter a valid email.',
            'email.email'       => 'Enter a valid email.',
            'email.unique'      => 'Email already exists.',
            'password.required' => 'Password must be at least 6 characters.',
            'password.min'      => 'Password must be at least 6 characters.',
        ]);

        $acc = User::create([
            'name'     => $payload['name'],
            'email'    => $payload['email'],
            'password' => Hash::make($payload['password']),
            'role'     => 'customer',
        ]);

        Auth::login($acc);
        return redirect()->route('home')->with('success', 'Registration complete.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Signed out.');
    }
}
