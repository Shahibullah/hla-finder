<?php

namespace App\Http\Controllers;

use App\Models\TransplantInfo;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalDonors = User::where('role', 'donor')->count();
        $totalReceivers = User::where('role', 'receiver')->count();
        $totalLabs = User::where('role', 'lab')->count();
        $pendingLabs = User::where('role', 'lab')->where('status', 'inactive')->count();
        $activeLabs = User::where('role', 'lab')->where('status', 'active')->count();
        $totalTransplants = TransplantInfo::count();

        return view('admin.dashboard', compact(
            'totalDonors',
            'totalReceivers',
            'totalLabs',
            'pendingLabs',
            'activeLabs',
            'totalTransplants'
        ));
    }

    public function labs()
    {
        $labs = User::where('role', 'lab')->latest()->get();

        return view('admin.labs', compact('labs'));
    }

    public function activateLab($id)
    {
        $lab = User::where('role', 'lab')->findOrFail($id);
        $lab->status = 'active';
        $lab->save();

        return redirect()->route('admin.labs')
            ->with('success', 'Lab activated successfully.');
    }

    public function deactivateLab($id)
    {
        $lab = User::where('role', 'lab')->findOrFail($id);
        $lab->status = 'inactive';
        $lab->save();

        return redirect()->route('admin.labs')
            ->with('success', 'Lab deactivated successfully.');
    }

    public function transplantHistory()
    {
        $histories = TransplantInfo::with(['donor', 'receiver', 'lab'])
            ->latest()
            ->get();

        return view('admin.transplant-history', compact('histories'));
    }
}