@extends('layouts.main')

@section('title', 'Receiver Requests')

@section('content')
<div style="padding: 40px 20px;">
    <div style="
        max-width: 1100px;
        margin: 0 auto;
        background: #ffffff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    ">
        <h1 style="
            font-size: 40px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 30px;
        ">
            Requests from Receivers
        </h1>

        @if($requests->isEmpty())
            <p style="
                font-size: 20px;
                text-align: center;
                color: #666;
                margin-bottom: 30px;
            ">
                No receiver requests found.
            </p>
        @else
            @foreach($requests as $request)
                <div style="
                    background: #f4f6f8;
                    border-left: 8px solid #95a5a6;
                    border-radius: 12px;
                    padding: 25px 30px;
                    margin-bottom: 25px;
                ">
                    <p style="font-size: 24px; margin-bottom: 12px;">
                        <strong>{{ $request->match_percentage }}%</strong> match request from
                        <strong>{{ $request->name }}</strong>
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>HLA Type:</strong> {{ $request->hla_type ?? 'Not assigned yet' }}
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>HLA Class:</strong> {{ $request->hla_class ?? 'Not assigned yet' }}
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>Phone:</strong> {{ $request->phone_no ?? 'N/A' }}
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>Email:</strong> {{ $request->email }}
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>Address:</strong> {{ $request->address_by_divisions ?? 'N/A' }}
                    </p>

                    <p style="font-size: 19px; margin: 6px 0;">
                        <strong>Status:</strong>
                        <span style="
                            font-weight: 700;
                            color:
                                {{ $request->status === 'accepted' ? '#28a745' : ($request->status === 'rejected' ? '#e74c3c' : '#f39c12') }};
                        ">
                            {{ ucfirst($request->status) }}
                        </span>
                    </p>
                </div>
            @endforeach
        @endif

        <a href="{{ route('donor.dashboard') }}" style="
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