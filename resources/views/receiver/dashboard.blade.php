@extends('layouts.main')

@section('title', 'Receiver Dashboard')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#94a3b8' : '#6c757d';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $borderColor = $isDark ? '#334155' : '#ddd';
    $boxBg = $isDark ? '#1e293b' : '#eef3f8';
@endphp

<div style="
    padding: 40px 20px;
    background: {{ $pageBg }};
    min-height: calc(100vh - 90px);
">
    <div style="
        max-width: 850px;
        margin: 0 auto;
        background: {{ $cardBg }};
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        color: {{ $cardText }};
    ">
        <h1 style="
            text-align: center;
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 40px;
            color: {{ $headingColor }};
        ">
            Receiver Dashboard
        </h1>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px; font-size: 20px; color: {{ $cardText }};">
            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="padding: 18px 0; color: {{ $mutedText }}; font-weight: 600;">Name</td>
                <td style="padding: 18px 0; text-align: right; font-weight: 600;">{{ auth()->user()->name }}</td>
            </tr>
            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="padding: 18px 0; color: {{ $mutedText }}; font-weight: 600;">Email</td>
                <td style="padding: 18px 0; text-align: right; font-weight: 600;">{{ auth()->user()->email }}</td>
            </tr>
            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="padding: 18px 0; color: {{ $mutedText }}; font-weight: 600;">Phone</td>
                <td style="padding: 18px 0; text-align: right; font-weight: 600;">{{ auth()->user()->phone_no ?? 'N/A' }}</td>
            </tr>
            <tr style="border-bottom: 1px solid {{ $borderColor }};">
                <td style="padding: 18px 0; color: {{ $mutedText }}; font-weight: 600;">Address</td>
                <td style="padding: 18px 0; text-align: right; font-weight: 600;">{{ auth()->user()->address_by_divisions ?? 'N/A' }}</td>
            </tr>
        </table>

        <div style="
            background: {{ $boxBg }};
            border-left: 6px solid #2d9cdb;
            padding: 18px 22px;
            border-radius: 12px;
            margin-bottom: 30px;
            font-size: 22px;
            font-weight: 700;
            color: {{ $cardText }};
        ">
            HLA Type: {{ auth()->user()->hla_type ?? 'Not assigned yet' }}
        </div>

        <div style="display: flex; flex-direction: column; gap: 18px;">
            <a href="{{ route('profile.edit') }}"
               style="
                    display: block;
                    width: 100%;
                    background: #3498db;
                    color: #fff;
                    text-align: center;
                    padding: 18px;
                    border-radius: 12px;
                    font-size: 20px;
                    font-weight: 700;
                    text-decoration: none;
               ">
                Update Personal Information
            </a>

            <a href="{{ route('receiver.match.status') }}"
               style="
                    display: block;
                    width: 100%;
                    background: #3498db;
                    color: #fff;
                    text-align: center;
                    padding: 18px;
                    border-radius: 12px;
                    font-size: 20px;
                    font-weight: 700;
                    text-decoration: none;
               ">
                View Match Status
            </a>

            <a href="{{ route('receiver.transplant.history') }}"
               style="
                    display: block;
                    width: 100%;
                    background: #3498db;
                    color: #fff;
                    text-align: center;
                    padding: 18px;
                    border-radius: 12px;
                    font-size: 20px;
                    font-weight: 700;
                    text-decoration: none;
               ">
                Personal Transplant History View
            </a>

            <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit"
                        style="
                            width: 100%;
                            background: #95a5a6;
                            color: #fff;
                            border: none;
                            padding: 18px;
                            border-radius: 12px;
                            font-size: 20px;
                            font-weight: 700;
                            cursor: pointer;
                        ">
                    Logout
                </button>
            </form>

            <form action="{{ route('account.deactivate') }}" method="POST"
                  onsubmit="return confirm('Are you sure you want to deactivate your account?');"
                  style="margin: 0;">
                @csrf
                <button type="submit"
                        style="
                            width: 100%;
                            background: #e74c3c;
                            color: #fff;
                            border: none;
                            padding: 18px;
                            border-radius: 12px;
                            font-size: 20px;
                            font-weight: 700;
                            cursor: pointer;
                        ">
                    Deactivate My Account
                </button>
            </form>
        </div>
    </div>
</div>
@endsection