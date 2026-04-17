@extends('layouts.main')

@section('title', 'Transplantation Action')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#cbd5e1' : '#555';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $inputBg = $isDark ? '#0f172a' : '#ffffff';
    $inputText = $isDark ? '#f8fafc' : '#111827';
    $inputBorder = $isDark ? '#334155' : '#d1d5db';
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
            font-size: 36px;
            font-weight: 700;
            color: {{ $headingColor }};
            margin-bottom: 25px;
            text-align: center;
        ">
            Transplantation Action
        </h1>

        <p style="
            font-size: 18px;
            color: {{ $mutedText }};
            margin-bottom: 25px;
            text-align: center;
        ">
            Select donor and receiver and record transplant details.
        </p>

        @if(session('success'))
            <div style="
                background:#14532d;
                color:#dcfce7;
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
                background:#7f1d1d;
                color:#fee2e2;
                padding:14px 18px;
                border-radius:10px;
                margin-bottom:20px;
                font-size:18px;
                font-weight:600;
            ">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('lab.transplant.action.store') }}" method="POST">
            @csrf

            <div style="margin-bottom: 18px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Select Donor
                </label>
                <select name="donor_id" required style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">
                    <option value="">-- Select Donor --</option>
                    @foreach($donors as $donor)
                        <option value="{{ $donor->id }}" {{ old('donor_id') == $donor->id ? 'selected' : '' }}>
                            {{ $donor->name }} ({{ $donor->hla_type ?? 'No HLA' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Select Receiver
                </label>
                <select name="receiver_id" required style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">
                    <option value="">-- Select Receiver --</option>
                    @foreach($receivers as $receiver)
                        <option value="{{ $receiver->id }}" {{ old('receiver_id') == $receiver->id ? 'selected' : '' }}>
                            {{ $receiver->name }} ({{ $receiver->hla_type ?? 'No HLA' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 18px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Transplant Date
                </label>
                <input type="date" name="transplant_date" value="{{ old('transplant_date') }}" required style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Organ Type
                </label>
                <input type="text" name="organ_type" value="{{ old('organ_type') }}" placeholder="e.g. Kidney" required style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">
            </div>

            <div style="margin-bottom: 18px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Outcome
                </label>
                <select name="outcome" required style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">
                    <option value="">-- Select Outcome --</option>
                    <option value="Successful" {{ old('outcome') == 'Successful' ? 'selected' : '' }}>Successful</option>
                    <option value="Failed" {{ old('outcome') == 'Failed' ? 'selected' : '' }}>Failed</option>
                    <option value="Ongoing" {{ old('outcome') == 'Ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="Rejected" {{ old('outcome') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="
                    font-weight: 700;
                    color: {{ $cardText }};
                    display: block;
                    margin-bottom: 6px;
                ">
                    Condition Notes
                </label>
                <textarea name="condition_notes" rows="4" placeholder="Optional notes..." style="
                    width: 100%;
                    padding: 12px;
                    border-radius: 10px;
                    border: 1px solid {{ $inputBorder }};
                    background: {{ $inputBg }};
                    color: {{ $inputText }};
                    margin-top: 6px;
                    box-sizing: border-box;
                ">{{ old('condition_notes') }}</textarea>
            </div>

            <button type="submit" style="
                background:#3498db;
                color:#fff;
                border:none;
                padding:14px 22px;
                border-radius:10px;
                font-size:18px;
                font-weight:700;
                cursor:pointer;
                width:100%;
            ">
                Save Transplant Record
            </button>
        </form>

        <div style="margin-top: 25px; text-align: center;">
            <a href="{{ route('lab.dashboard') }}" style="
                color:#3498db;
                font-size:18px;
                font-weight:700;
                text-decoration:none;
            ">
                ← Back to Dashboard
            </a>
        </div>
    </div>
</div>
@endsection