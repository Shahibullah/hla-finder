<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['donor', 'receiver'])) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'dob' => 'nullable|date',
            'sex' => 'nullable|in:Male,Female,Others',
            'phone_no' => 'nullable|string|max:20',
            'address_by_divisions' => 'nullable|string|max:100',
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }
}