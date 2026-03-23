<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        // Prevent admin self-deactivation for safety
        if ($user->role === 'admin') {
            return back()->withErrors([
                'error' => 'Admin account cannot be self-deactivated.',
            ]);
        }

        $user->status = 'inactive';
        $user->save();

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Your account has been deactivated successfully.');
    }
}