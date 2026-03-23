<?php

namespace App\Http\Controllers;

use App\Models\User;

class Donor_DashboardController extends Controller
{
    public function home()
    {
        $donorCount = User::where('role', 'donor')
                          ->where('status', 'active')
                          ->count();

        return view('home', compact('donorCount'));
    }

    public function dashboard()
    {
        $totalDonors = User::where('role','donor')->count();
        $totalReceivers = User::where('role','receiver')->count();
        $totalLabs = User::where('role','lab')->count();

        $pendingLabs = User::where('role','lab')
                            ->where('status','inactive')
                            ->count();

        $activeLabs = User::where('role','lab')
                            ->where('status','active')
                            ->count();

        return view('admin.dashboard', compact(
            'totalDonors',
            'totalReceivers',
            'totalLabs',
            'pendingLabs',
            'activeLabs'
        ));
    }
}
