@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="card">
        <h1>Admin Dashboard</h1>
        
        <h3>System Statistics</h3>

<table border="1" cellpadding="10" width="400">
    <tr>
        <th>Total Donors</th>
        <td>{{ $totalDonors }}</td>
    </tr>
    <tr>
        <th>Total Receivers</th>
        <td>{{ $totalReceivers }}</td>
    </tr>
    <tr>
        <th>Total Labs</th>
        <td>{{ $totalLabs }}</td>
    </tr>
    <tr>
        <th>Pending Labs</th>
        <td>{{ $pendingLabs }}</td>
    </tr>
    <tr>
        <th>Active Labs</th>
        <td>{{ $activeLabs }}</td>
    </tr>
</table><br>
        <a href="{{ route('admin.users.index') }}" class="btn">Manage Users</a>
    </div>
@endsection