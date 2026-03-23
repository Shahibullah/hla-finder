<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LabHlaController extends Controller
{
    public function index(Request $request)
    {
        $lab = Auth::user();

        if ($lab->status !== 'active') {
            abort(403, 'Only active labs can access this page.');
        }

        $search = $request->query('search');

        $users = User::whereIn('role', ['donor', 'receiver'])
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            })
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return view('lab.hla.index', compact('users', 'search'));
    }

    public function update(Request $request, $id)
    {
        $lab = Auth::user();

        if ($lab->status !== 'active') {
            abort(403, 'Only active labs can update HLA data.');
        }

        $validated = $request->validate([
            'hla_type' => 'required|string|max:50',
            'hla_class' => 'required|in:Class-i,Class-ii',
        ]);

        $user = User::whereIn('role', ['donor', 'receiver'])->findOrFail($id);

        $user->hla_type = $validated['hla_type'];
        $user->hla_class = $validated['hla_class'];
        $user->save();

        return redirect()->route('lab.hla.index')
            ->with('success', 'HLA information updated successfully for ' . $user->name . '.');
    }
}