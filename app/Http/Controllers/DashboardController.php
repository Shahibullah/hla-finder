<?php

namespace App\Http\Controllers;

use App\Models\TransplantInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function home()
    {
        $activeDonors = User::where('role', 'donor')
            ->where('status', 'active')
            ->count();

        return view('welcome', compact('activeDonors'));
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role === 'admin') {
            $totalDonors = User::where('role', 'donor')->count();
            $totalReceivers = User::where('role', 'receiver')->count();
            $totalLabs = User::where('role', 'lab')->count();
            $pendingLabs = User::where('role', 'lab')->where('status', 'inactive')->count();
            $activeLabs = User::where('role', 'lab')->where('status', 'active')->count();
            $totalTransplants = class_exists(\App\Models\TransplantInfo::class)
                ? TransplantInfo::count()
                : 0;

            return view('admin.dashboard', compact(
                'totalDonors',
                'totalReceivers',
                'totalLabs',
                'pendingLabs',
                'activeLabs',
                'totalTransplants'
            ));
        }

        if ($user->role === 'donor') {
            return view('donor.dashboard');
        }

        if ($user->role === 'receiver') {
            return view('receiver.dashboard');
        }

        if ($user->role === 'lab') {
            return view('lab.dashboard');
        }

        abort(403, 'Unauthorized access.');
    }
}