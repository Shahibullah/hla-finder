<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function requests()
    {
        $donor = Auth::user();

        if (!$donor || $donor->role !== 'donor') {
            abort(403, 'Only donors can view requests.');
        }

        $requests = DB::table('match_requests')
            ->join('users as receivers', 'match_requests.receiver_id', '=', 'receivers.id')
            ->select(
                'match_requests.id',
                'match_requests.match_percentage',
                'match_requests.status',
                'match_requests.created_at',
                'receivers.id as receiver_id',
                'receivers.name',
                'receivers.email',
                'receivers.phone_no',
                'receivers.address_by_divisions',
                'receivers.hla_type',
                'receivers.hla_class'
            )
            ->where('match_requests.donor_id', $donor->id)
            ->orderByDesc('match_requests.id')
            ->get();

        return view('donor.requests', compact('requests', 'donor'));
    }
}