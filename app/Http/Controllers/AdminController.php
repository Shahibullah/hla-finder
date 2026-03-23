<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function labs()
    {
        $labs = User::where('role', 'lab')->get();

        return view('admin.labs', compact('labs'));
    }

    public function activateLab($id)
    {
        $lab = User::findOrFail($id);

        $lab->status = 'active';
        $lab->save();

        return back()->with('success', 'Lab activated successfully.');
    }

    public function deactivateLab($id)
    {
        $lab = User::findOrFail($id);

        $lab->status = 'inactive';
        $lab->save();

        return back()->with('success', 'Lab deactivated successfully.');
    }
}