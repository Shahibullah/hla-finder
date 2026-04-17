@extends('layouts.main')

@section('title', 'Lab HLA Typing')

@section('content')
<div style="padding: 30px 20px;">
    <div style="
        max-width: 1250px;
        margin: 0 auto;
        background: #0f172a;
        border-radius: 18px;
        padding: 24px;
        color: #ffffff;
        box-shadow: 0 4px 18px rgba(0,0,0,0.15);
    ">
        <h1 style="
            margin: 0 0 10px 0;
            font-size: 34px;
            font-weight: 700;
            color: #e5e7eb;
        ">
            Lab HLA Typing
        </h1>

        <p style="
            margin: 0 0 18px 0;
            color: #cbd5e1;
            font-size: 15px;
        ">
            Search donor or receiver accounts and assign/update HLA Type and HLA Class.
        </p>

        @if(session('success'))
            <div style="
                background: #14532d;
                color: #dcfce7;
                padding: 12px 16px;
                border-radius: 10px;
                margin-bottom: 18px;
                font-weight: 600;
            ">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div style="
                background: #7f1d1d;
                color: #fee2e2;
                padding: 12px 16px;
                border-radius: 10px;
                margin-bottom: 18px;
                font-weight: 600;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('lab.hla.index') }}" method="GET" style="
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
        ">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search by name or email"
                style="
                    flex: 1;
                    min-width: 260px;
                    background: #1e293b;
                    color: #ffffff;
                    border: 1px solid #334155;
                    border-radius: 10px;
                    padding: 12px 14px;
                    outline: none;
                "
            >

            <button type="submit" style="
                background: #2563eb;
                color: #ffffff;
                border: none;
                border-radius: 10px;
                padding: 11px 18px;
                font-weight: 700;
                cursor: pointer;
            ">
                Search
            </button>

            <a href="{{ route('lab.hla.index') }}" style="
                background: #22c55e;
                color: #ffffff;
                text-decoration: none;
                border-radius: 10px;
                padding: 11px 18px;
                font-weight: 700;
                display: inline-block;
            ">
                Reset
            </a>
        </form>

        <div style="
            background: #111827;
            border-radius: 16px;
            padding: 20px;
            overflow-x: auto;
        ">
            <h2 style="
                margin: 0 0 18px 0;
                font-size: 24px;
                font-weight: 700;
                color: #f8fafc;
            ">
                Users
            </h2>

            @if($users->isEmpty())
                <p style="color: #cbd5e1; margin: 0;">No users found.</p>
            @else
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    color: #e5e7eb;
                    min-width: 1000px;
                ">
                    <thead>
                        <tr style="border-bottom: 1px solid #334155;">
                            <th style="text-align: left; padding: 12px;">Name</th>
                            <th style="text-align: left; padding: 12px;">Role</th>
                            <th style="text-align: left; padding: 12px;">Email</th>
                            <th style="text-align: left; padding: 12px;">Current HLA</th>
                            <th style="text-align: left; padding: 12px;">Update HLA</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr style="border-bottom: 1px solid #1f2937;">
                                <td style="padding: 14px 12px;">{{ $user->name }}</td>

                                <td style="padding: 14px 12px;">
                                    <span style="
                                        display: inline-block;
                                        padding: 4px 10px;
                                        border-radius: 999px;
                                        font-size: 12px;
                                        font-weight: 700;
                                        background: {{ $user->role === 'donor' ? '#14532d' : '#1d4ed8' }};
                                        color: #ffffff;
                                    ">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td style="padding: 14px 12px;">{{ $user->email }}</td>

                                <td style="padding: 14px 12px;">
                                    <div><strong>Type:</strong> {{ $user->hla_type ?? 'Not set' }}</div>
                                    <div><strong>Class:</strong> {{ $user->hla_class ?? 'Not set' }}</div>
                                </td>

                                <td style="padding: 14px 12px;">
                                    <form action="{{ route('lab.hla.update', $user->id) }}" method="POST" style="
                                        display: flex;
                                        gap: 8px;
                                        align-items: center;
                                        flex-wrap: wrap;
                                    ">
                                        @csrf

                                        <input
                                            type="text"
                                            name="hla_type"
                                            placeholder="Enter HLA Type"
                                            style="
                                                min-width: 180px;
                                                background: #1e293b;
                                                color: #ffffff;
                                                border: 1px solid #334155;
                                                border-radius: 8px;
                                                padding: 10px 12px;
                                                outline: none;
                                            "
                                        >

                                        <select
                                            name="hla_class"
                                            style="
                                                min-width: 170px;
                                                background: #1e293b;
                                                color: #ffffff;
                                                border: 1px solid #334155;
                                                border-radius: 8px;
                                                padding: 10px 12px;
                                                outline: none;
                                            "
                                        >
                                            <option value="">Select HLA Class</option>
                                            <option value="Class-i">Class-i</option>
                                            <option value="Class-ii">Class-ii</option>
                                        </select>

                                        <button type="submit" style="
                                            background: #22c55e;
                                            color: #ffffff;
                                            border: none;
                                            border-radius: 8px;
                                            padding: 10px 16px;
                                            font-weight: 700;
                                            cursor: pointer;
                                        ">
                                            Save
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div style="margin-top: 20px;">
            <a href="{{ route('lab.dashboard') }}" style="
                color: #93c5fd;
                text-decoration: none;
                font-weight: 700;
            ">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection