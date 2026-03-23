<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $users = User::when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%')
                      ->orWhere('role', 'like', '%' . $search . '%');
                });
            })
            ->orderByRaw("FIELD(role, 'admin', 'lab', 'donor', 'receiver')")
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact('users', 'search'));
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->withErrors([
                'error' => 'Admin accounts cannot be activated or deactivated here.',
            ]);
        }

        $user->status = 'active';
        $user->save();

        return back()->with('success', $user->name . ' has been activated successfully.');
    }

    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin') {
            return back()->withErrors([
                'error' => 'Admin accounts cannot be activated or deactivated here.',
            ]);
        }

        $user->status = 'inactive';
        $user->save();

        return back()->with('success', $user->name . ' has been deactivated successfully.');
    }
}