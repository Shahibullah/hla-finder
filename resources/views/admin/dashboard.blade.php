@extends('layouts.main')

@section('title', 'Admin Dashboard')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#cbd5e1' : '#334155';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $borderColor = $isDark ? '#334155' : '#d1d5db';
@endphp

<div style="
    padding: 40px 20px;
    background: {{ $pageBg }};
    min-height: calc(100vh - 90px);
">
    <div style="
        max-width: 1200px;
        margin: 0 auto;
        background: {{ $cardBg }};
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        color: {{ $cardText }};
    ">
        <h1 style="
            margin-top: 0;
            font-size: 42px;
            font-weight: 700;
            color: {{ $headingColor }};
            margin-bottom: 20px;
        ">
            Admin Dashboard
        </h1>

        <h2 style="
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
            color: {{ $mutedText }};
        ">
            System Statistics
        </h2>

        <div style="overflow-x: auto; margin-bottom: 25px;">
            <table style="
                width: 100%;
                max-width: 550px;
                border-collapse: collapse;
                background: {{ $cardBg }};
                color: {{ $cardText }};
            ">
                <tbody>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Total Donors</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $totalDonors ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Total Receivers</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $totalReceivers ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Total Labs</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $totalLabs ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Pending Labs</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $pendingLabs ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Active Labs</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $activeLabs ?? 0 }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }}; font-weight: 600;">Total Transplants</td>
                        <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $totalTransplants ?? 0 }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
        ">
            <a href="{{ route('admin.users.index') }}" style="
                display: inline-block;
                background: #3498db;
                color: #fff;
                padding: 12px 20px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 700;
            ">
                Manage Users
            </a>

            <a href="{{ route('admin.labs') }}" style="
                display: inline-block;
                background: #3498db;
                color: #fff;
                padding: 12px 20px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 700;
            ">
                Manage Labs
            </a>

            <a href="{{ route('admin.transplant.history') }}" style="
                display: inline-block;
                background: #3498db;
                color: #fff;
                padding: 12px 20px;
                border-radius: 10px;
                text-decoration: none;
                font-weight: 700;
            ">
                View Transplant History
            </a>
        </div>
    </div>
</div>
@endsection