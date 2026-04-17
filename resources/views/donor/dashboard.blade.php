@extends('layouts.main')

@section('title', 'Donor Dashboard')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#94a3b8' : '#6c757d';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $borderColor = $isDark ? '#334155' : '#ddd';
@endphp

<div style="
    padding: 40px 20px;
    background: {{ $pageBg }};
    min-height: calc(100vh - 90px);
">
    <div style="
        max-width: 900px;
        margin: 0 auto;
        background: {{ $cardBg }};
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        color: {{ $cardText }};
    ">

        <h1 style="
            text-align:center;
            font-size:40px;
            font-weight:700;
            margin-bottom:30px;
            color: {{ $headingColor }};
        ">
            Donor Dashboard
        </h1>

        <table style="width:100%; font-size:18px; margin-bottom:30px; color: {{ $cardText }};">
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid {{ $borderColor }};"><strong>Name:</strong></td>
                <td style="padding: 10px 0; text-align:right; border-bottom: 1px solid {{ $borderColor }};">{{ auth()->user()->name }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid {{ $borderColor }};"><strong>Status:</strong></td>
                <td style="padding: 10px 0; text-align:right; border-bottom: 1px solid {{ $borderColor }};">{{ auth()->user()->status }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid {{ $borderColor }};"><strong>HLA Type:</strong></td>
                <td style="padding: 10px 0; text-align:right; border-bottom: 1px solid {{ $borderColor }};">{{ auth()->user()->hla_type ?? 'Not assigned yet' }}</td>
            </tr>
            <tr>
                <td style="padding: 10px 0; border-bottom: 1px solid {{ $borderColor }};"><strong>HLA Class:</strong></td>
                <td style="padding: 10px 0; text-align:right; border-bottom: 1px solid {{ $borderColor }};">{{ auth()->user()->hla_class ?? 'Not assigned yet' }}</td>
            </tr>
        </table>

        <div style="display:flex; flex-direction:column; gap:15px;">

            <a href="{{ route('profile.edit') }}" style="
                display:block;
                background:#3498db;
                color:white;
                padding:15px;
                text-align:center;
                border-radius:10px;
                font-weight:bold;
                text-decoration:none;
            ">
                Edit Profile
            </a>

            <a href="{{ route('donor.requests') }}" style="
                display:block;
                background:#3498db;
                color:white;
                padding:15px;
                text-align:center;
                border-radius:10px;
                font-weight:bold;
                text-decoration:none;
            ">
                View Requests from Receivers
            </a>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" style="
                    width:100%;
                    background:#95a5a6;
                    color:white;
                    padding:15px;
                    border:none;
                    border-radius:10px;
                    font-weight:bold;
                    cursor:pointer;
                ">
                    Logout
                </button>
            </form>

            <form action="{{ route('account.deactivate') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to deactivate your account?');">
                @csrf
                <button type="submit" style="
                    width:100%;
                    background:#e74c3c;
                    color:white;
                    padding:15px;
                    border:none;
                    border-radius:10px;
                    font-weight:bold;
                    cursor:pointer;
                ">
                    Deactivate My Account
                </button>
            </form>

        </div>
    </div>
</div>
@endsection