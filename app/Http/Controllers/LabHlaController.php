<?php

namespace App\Http\Controllers;

use App\Models\TransplantInfo;
use App\Models\User;
use Illuminate\Http\Request;

class LabHlaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

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

        return view('lab.hla.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hla_type' => 'required|string|max:255',
            'hla_class' => 'required|in:Class-i,Class-ii',
        ]);

        $user = User::whereIn('role', ['donor', 'receiver'])->findOrFail($id);

        $user->hla_type = $request->hla_type;
        $user->hla_class = $request->hla_class;
        $user->save();

        return redirect()->route('lab.hla.index')
            ->with('success', 'HLA information updated successfully for ' . $user->name . '.');
    }

    public function transplantAction()
    {
        $donors = User::where('role', 'donor')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        $receivers = User::where('role', 'receiver')
            ->where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('lab.transplant-action', compact('donors', 'receivers'));
    }

    public function storeTransplantAction(Request $request)
    {
        $request->validate([
            'donor_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id|different:donor_id',
            'transplant_date' => 'required|date',
            'organ_type' => 'required|string|max:255',
            'outcome' => 'required|string|max:255',
            'condition_notes' => 'nullable|string',
        ]);

        $donor = User::where('role', 'donor')->findOrFail($request->donor_id);
        $receiver = User::where('role', 'receiver')->findOrFail($request->receiver_id);

        TransplantInfo::create([
            'donor_id' => $donor->id,
            'receiver_id' => $receiver->id,
            'lab_id' => auth()->id(),
            'transplant_date' => $request->transplant_date,
            'organ_type' => $request->organ_type,
            'outcome' => $request->outcome,
            'condition_notes' => $request->condition_notes,
        ]);

        return redirect()->route('lab.transplant.history')
            ->with('success', 'Transplant record saved successfully.');
    }

    public function transplantHistory()
    {
        $histories = TransplantInfo::with(['donor', 'receiver', 'lab'])
            ->latest()
            ->get();

        return view('lab.transplant-history', compact('histories'));
    }
}