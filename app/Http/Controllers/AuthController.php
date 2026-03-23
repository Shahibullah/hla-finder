<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'role' => 'required|in:donor,receiver,lab',
            'name' => 'required|string|max:100',
            'dob' => 'nullable|date',
            'sex' => 'nullable|in:Male,Female,Others',
            'phone_no' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'address_by_divisions' => 'nullable|string|max:100',
            'lab_name' => 'nullable|string|max:150',
            'lab_address' => 'nullable|string|max:150',
        ]);

        $status = $request->role === 'lab' ? 'inactive' : 'active';

        $user = User::create([
            'role' => $request->role,
            'name' => $request->name,
            'dob' => $request->dob,
            'sex' => $request->sex,
            'phone_no' => $request->phone_no,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => $status,
            'address_by_divisions' => $request->address_by_divisions,
            'lab_name' => $request->role === 'lab' ? $request->lab_name : null,
            'lab_address' => $request->role === 'lab' ? $request->lab_address : null,
            'theme' => 'light',
        ]);

        if ($user->role === 'lab') {
            return redirect()->route('login')->with('success', 'Lab registered successfully. Wait for admin approval.');
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ])->onlyInput('email');
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}