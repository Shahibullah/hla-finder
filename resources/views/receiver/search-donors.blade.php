@extends('layouts.app')

@section('content')
    <h2>Search Donors by HLA</h2>

    <form method="GET" action="{{ route('receiver.search') }}">
        <label>HLA Type</label>
        <input type="text" name="hla_type" value="{{ request('hla_type', $receiver->hla_type) }}">

        <label>HLA Class</label>
        <select name="hla_class">
            <option value="">Select HLA Class</option>
            <option value="Class-i" {{ request('hla_class', $receiver->hla_class) === 'Class-i' ? 'selected' : '' }}>Class-i</option>
            <option value="Class-ii" {{ request('hla_class', $receiver->hla_class) === 'Class-ii' ? 'selected' : '' }}>Class-ii</option>
        </select>

        <button type="submit">Search</button>
    </form>

    <hr>

    <h3>Matched Donors</h3>

    @forelse($donors as $donor)
        <div style="border:1px solid #ddd; padding:15px; margin-bottom:15px; border-radius:8px;">
            <p><strong>Name:</strong> {{ $donor->name }}</p>
            <p><strong>Email:</strong> {{ $donor->email }}</p>
            <p><strong>Phone:</strong> {{ $donor->phone_no ?? 'N/A' }}</p>
            <p><strong>HLA Type:</strong> {{ $donor->hla_type ?? 'N/A' }}</p>
            <p><strong>HLA Class:</strong> {{ $donor->hla_class ?? 'N/A' }}</p>
            <p><strong>Match Percentage:</strong> {{ $donor->match_percentage }}%</p>
        </div>
    @empty
        <p>No active donors found.</p>
    @endforelse
@endsection