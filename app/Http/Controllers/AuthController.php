<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ====================== SHOW REGISTER FORM ======================
    public function showRegister()
    {
        return view('auth.register');
    }

    // ====================== REGISTER (Only Job Seeker) ======================
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'job_seeker',           // ← ONLY job_seeker can register here
        ]);

        Auth::login($user);

        return redirect()->route('cv.create')->with('success', 'Registration successful! Create your CV now.');
    }

    // ====================== SHOW LOGIN FORM ======================
    public function showLogin()
    {
        return view('auth.login');
    }

    // ====================== LOGIN ======================
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            // RBAC Redirect based on role
            if ($user->role === 'job_seeker') {
                return redirect()->route('cv.create');
            } elseif ($user->role === 'employer') {
                return redirect()->route('employer.search');   // Person C 
            } elseif ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');   // Person A 
            }
        }

        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    // ====================== LOGOUT ======================
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'Logged out successfully');
    }
}