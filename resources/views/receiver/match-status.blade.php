@extends('layouts.main')

@section('title', 'HLA Match Status')

@section('content')
<div style="padding: 40px 20px;">
    <div style="
        max-width: 1200px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        padding: 45px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    ">
        <h1 style="
            font-size: 38px;
            font-weight: 700;
            color: #1f3b5b;
            margin-bottom: 25px;
        ">
            HLA Match Status
        </h1>

        <p style="
            font-size: 20px;
            margin-bottom: 20px;
            color: #111;
        ">
            <strong>Your HLA Type:</strong> {{ $user->hla_type ?? 'Not assigned yet' }}
        </p>

        @if(session('success'))
            <div style="
                background:#d4edda;
                color:#155724;
                padding:14px 18px;
                border-radius:10px;
                margin-bottom:20px;
                font-size:18px;
                font-weight:600;
            ">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="
                background:#f8d7da;
                color:#721c24;
                padding:14px 18px;
                border-radius:10px;
                margin-bottom:20px;
                font-size:18px;
                font-weight:600;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        <hr style="margin-bottom: 20px; border: 1px solid #ddd;">

        @if(count($matchedDonors) > 0)
            @foreach($matchedDonors as $donor)
                <div style="
                    background: #f3f5f7;
                    border-left: 8px solid #95a5a6;
                    border-radius: 12px;
                    padding: 28px 30px;
                    margin-bottom: 30px;
                ">
                    <p style="font-size: 22px; margin-bottom: 10px;">
                        <strong>{{ $donor['match_percentage'] }}%</strong> HLA match with <strong>{{ $donor['name'] }}</strong>
                    </p>

                    <p style="font-size: 20px; margin: 6px 0;">
                        <strong>HLA Type:</strong> {{ $donor['hla_type'] ?? 'Not assigned yet' }}
                    </p>

                    <p style="font-size: 20px; margin: 6px 0;">
                        <strong>Phone:</strong> {{ $donor['phone_no'] ?? 'N/A' }}
                    </p>

                    <p style="font-size: 20px; margin: 6px 0;">
                        <strong>Email:</strong> {{ $donor['email'] }}
                    </p>

                    <p style="font-size: 20px; margin: 6px 0;">
                        <strong>Address:</strong> {{ $donor['address_by_divisions'] ?? 'N/A' }}
                    </p>

                    <p style="font-size: 20px; margin: 6px 0;">
                        <strong>Status:</strong>
                        <span style="color: #2eaf57; font-weight: 700;">Active Donor</span>
                    </p>

                    <form action="{{ route('receiver.contact.donor', $donor['id']) }}" method="POST" style="margin-top: 20px;">
                        @csrf
                        <button type="submit" style="
                            background:#3498db;
                            color:#fff;
                            border:none;
                            padding:12px 24px;
                            border-radius:10px;
                            font-size:18px;
                            font-weight:700;
                            cursor:pointer;
                        ">
                            Request
                        </button>
                    </form>
                </div>
            @endforeach
        @else
            <p style="font-size:20px; color:#666;">No matching donors found.</p>
        @endif

        <a href="{{ route('receiver.dashboard') }}" style="
            color: #3498db;
            font-size: 20px;
            font-weight: 700;
            text-decoration: none;
        ">
            ← Back to Dashboard
        </a>
    </div>
</div>
@endsection