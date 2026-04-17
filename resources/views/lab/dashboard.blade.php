@extends('layouts.main')

@section('title', 'Lab Dashboard')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#94a3b8' : '#7a8793';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $borderColor = $isDark ? '#334155' : '#e5e7eb';
    $statusColor = auth()->user()->status === 'active' ? '#16a34a' : '#dc2626';
@endphp

<div style="
    padding: 40px 20px;
    background: {{ $pageBg }};
    min-height: calc(100vh - 90px);
">
    <div style="
        max-width: 1100px;
        margin: 0 auto;
        background: {{ $cardBg }};
        border-radius: 22px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        color: {{ $cardText }};
    ">
        <h1 style="
            text-align: center;
            font-size: 42px;
            font-weight: 700;
            color: {{ $headingColor }};
            margin-top: 0;
            margin-bottom: 35px;
        ">
            Lab Dashboard
        </h1>

        <table style="
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            font-size: 18px;
            color: {{ $cardText }};
        ">
            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="
                    padding: 18px 14px;
                    width: 45%;
                    color: {{ $mutedText }};
                    font-weight: 700;
                ">
                    Lab Name
                </td>
                <td style="
                    padding: 18px 14px;
                    color: {{ $cardText }};
                    font-weight: 500;
                ">
                    {{ auth()->user()->lab_name ?? auth()->user()->name }}
                </td>
            </tr>

            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="
                    padding: 18px 14px;
                    color: {{ $mutedText }};
                    font-weight: 700;
                ">
                    Address
                </td>
                <td style="
                    padding: 18px 14px;
                    color: {{ $cardText }};
                    font-weight: 500;
                ">
                    {{ auth()->user()->lab_address ?? auth()->user()->address_by_divisions ?? 'N/A' }}
                </td>
            </tr>

            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="
                    padding: 18px 14px;
                    color: {{ $mutedText }};
                    font-weight: 700;
                ">
                    Email
                </td>
                <td style="
                    padding: 18px 14px;
                    color: {{ $cardText }};
                    font-weight: 500;
                ">
                    {{ auth()->user()->email }}
                </td>
            </tr>

            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="
                    padding: 18px 14px;
                    color: {{ $mutedText }};
                    font-weight: 700;
                ">
                    Status
                </td>
                <td style="
                    padding: 18px 14px;
                    font-weight: 700;
                    color: {{ $statusColor }};
                ">
                    {{ ucfirst(auth()->user()->status) }}
                </td>
            </tr>
        </table>

        <h2 style="
            font-size: 22px;
            font-weight: 700;
            color: {{ $headingColor }};
            margin-bottom: 18px;
        ">
            Lab Actions
        </h2>

        <div style="
            display: flex;
            flex-direction: column;
            gap: 14px;
            max-width: 420px;
        ">
            <a href="{{ route('lab.hla.index') }}" style="
                display: block;
                background: #3498db;
                color: #ffffff;
                text-decoration: none;
                padding: 14px 20px;
                border-radius: 10px;
                font-size: 18px;
                font-weight: 700;
                text-align: center;
            ">
                Set Donor / Receiver HLA Type
            </a>

            <a href="{{ route('lab.transplant.action') }}" style="
                display: block;
                background: #3498db;
                color: #ffffff;
                text-decoration: none;
                padding: 14px 20px;
                border-radius: 10px;
                font-size: 18px;
                font-weight: 700;
                text-align: center;
                max-width: 330px;
            ">
                Transplantation Action
            </a>

            <a href="{{ route('lab.transplant.history') }}" style="
                display: block;
                background: #3498db;
                color: #ffffff;
                text-decoration: none;
                padding: 14px 20px;
                border-radius: 10px;
                font-size: 18px;
                font-weight: 700;
                text-align: center;
                max-width: 340px;
            ">
                View Transplant History
            </a>

            <form action="{{ route('account.deactivate') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to deactivate your account?');"
                  style="margin: 0;">
                @csrf
                <button type="submit" style="
                    width: 100%;
                    background: #e74c3c;
                    color: #fff;
                    border: none;
                    padding: 14px 20px;
                    border-radius: 10px;
                    font-size: 18px;
                    font-weight: 700;
                    cursor: pointer;
                    max-width: 340px;
                ">
                    Deactivate My Account
                </button>
            </form>
        </div>

        <div style="
            margin-top: 34px;
            text-align: center;
        ">
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" style="
                    background: transparent;
                    border: none;
                    color: #3498db;
                    font-size: 18px;
                    font-weight: 700;
                    cursor: pointer;
                ">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>
@endsection