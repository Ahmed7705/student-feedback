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
        // Check login credentials
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home')->with('success', 'Welcome back.');
        }

        return redirect()->route('login')->withErrors(['email' => 'Wrong email or password.'])->onlyInput('email');
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
        $data = $request->validate([
            'name'     => 'required|min:3|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ], [
            'name.required'     => 'Full name must be at least 3 characters.',
            'name.min'          => 'Full name must be at least 3 characters.',
            'email.required'    => 'Please enter a valid email address.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'Email is already registered.',
            'password.required' => 'Password must be at least 6 characters.',
            'password.min'      => 'Password must be at least 6 characters.',
        ]);

        // Create new user
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        return redirect()->route('home')->with('success', 'Account created.');
    }

    public function logout(Request $request)
    {
        // End session
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Logged out.');
    }
}
