@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="card">
        <h1>Admin Dashboard</h1>
        <p>Welcome, Admin.</p>
        <a href="{{ route('admin.users.index') }}" class="btn">Manage Users</a>
    </div>
@endsection