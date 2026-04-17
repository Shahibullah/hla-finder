<?php

namespace App\Http\Controllers;

use App\Models\TransplantInfo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceiverController extends Controller
{
    public function matchStatus()
    {
        $user = Auth::user();

        $donors = User::where('role', 'donor')
            ->where('status', 'active')
            ->whereNotNull('hla_type')
            ->get();

        $matchedDonors = [];

        foreach ($donors as $donor) {
            $matchPercentage = $this->calculateHlaMatchPercentage(
                $user->hla_type,
                $donor->hla_type
            );

            if ($matchPercentage > 0) {
                $matchedDonors[] = [
                    'id' => $donor->id,
                    'name' => $donor->name,
                    'email' => $donor->email,
                    'phone_no' => $donor->phone_no,
                    'address_by_divisions' => $donor->address_by_divisions,
                    'hla_type' => $donor->hla_type,
                    'status' => $donor->status,
                    'match_percentage' => $matchPercentage,
                ];
            }
        }

        usort($matchedDonors, function ($a, $b) {
            return $b['match_percentage'] <=> $a['match_percentage'];
        });

        return view('receiver.match-status', compact('user', 'matchedDonors'));
    }

    public function requestDonor($donorId)
    {
        $receiver = Auth::user();
        $donor = User::findOrFail($donorId);

        if ($receiver->role !== 'receiver') {
            abort(403, 'Only receivers can send requests.');
        }

        if ($donor->role !== 'donor') {
            return back()->withErrors([
                'error' => 'Selected user is not a donor.',
            ]);
        }

        if ($donor->status !== 'active') {
            return back()->withErrors([
                'error' => 'This donor is not active.',
            ]);
        }

        $matchPercentage = $this->calculateHlaMatchPercentage(
            $receiver->hla_type,
            $donor->hla_type
        );

        $alreadyRequested = DB::table('match_requests')
            ->where('receiver_id', $receiver->id)
            ->where('donor_id', $donor->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($alreadyRequested) {
            return back()->withErrors([
                'error' => 'You have already sent a request to this donor.',
            ]);
        }

        DB::table('match_requests')->insert([
            'receiver_id' => $receiver->id,
            'donor_id' => $donor->id,
            'match_percentage' => $matchPercentage,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('receiver.match.status')
            ->with('success', 'Request sent successfully.');
    }

    public function transplantHistory()
    {
        $histories = TransplantInfo::with(['donor', 'receiver', 'lab'])
            ->where('receiver_id', auth()->id())
            ->latest()
            ->get();

        return view('receiver.transplant-history', compact('histories'));
    }

    private function calculateHlaMatchPercentage($receiverHla, $donorHla)
    {
        if (!$receiverHla || !$donorHla) {
            return 0;
        }

        $receiverHla = trim($receiverHla);
        $donorHla = trim($donorHla);

        $pattern = '/^(HLA-[A-Z0-9]+)\*(\d+):(\d+)$/';

        if (!preg_match($pattern, $receiverHla, $receiverMatches) ||
            !preg_match($pattern, $donorHla, $donorMatches)) {
            return 0;
        }

        $receiverGroup = $receiverMatches[1];
        $receiverPart1 = $receiverMatches[2];
        $receiverPart2 = $receiverMatches[3];

        $donorGroup = $donorMatches[1];
        $donorPart1 = $donorMatches[2];
        $donorPart2 = $donorMatches[3];

        $percentage = 0;

        if ($receiverGroup === $donorGroup) {
            $percentage += 20;
        } else {
            return 0;
        }

        if ($receiverPart1 === $donorPart1) {
            $percentage += 40;
        }

        if ($receiverPart2 === $donorPart2) {
            $percentage += 40;
        }

        return $percentage;
    }
}