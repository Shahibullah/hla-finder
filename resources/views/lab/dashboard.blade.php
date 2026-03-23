@extends('layouts.main')

@section('title', 'Lab Dashboard')

@section('content')
    <div class="card">
        <h1>Lab Dashboard</h1>
        <p>Welcome, Lab User.</p>

        <a href="{{ route('lab.hla.index') }}" class="btn">Set HLA Type</a>
        <form action="{{ route('account.deactivate') }}" method="POST"
              onsubmit="return confirm('Are you sure you want to deactivate your account?');"
              style="margin-top: 15px;">
            @csrf
            <button type="submit" class="btn red">Deactivate My Account</button>
    </div>
@endsection