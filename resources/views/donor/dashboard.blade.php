@extends('layouts.main')

@section('title', 'Donor Dashboard')



@section('content')
    <div class="card">
        <h1>Donor Dashboard</h1>
        <p>Welcome, Donor.</p>
        <a href="{{ route('profile.edit') }}" class="btn">Edit Profile</a>
        <form action="{{ route('account.deactivate') }}" method="POST"
              onsubmit="return confirm('Are you sure you want to deactivate your account?');"
              style="margin-top: 15px;">
            @csrf
            <button type="submit" class="btn red">Deactivate My Account</button>
        </form>
    </div>
@endsection