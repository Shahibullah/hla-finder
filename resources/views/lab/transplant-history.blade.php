@extends('layouts.main')

@section('title', 'Lab Transplant History')

@section('content')
@php
    $isDark = session('theme', 'light') === 'dark';

    $pageBg = $isDark ? '#0b0b0b' : '#e9edf3';
    $cardBg = $isDark ? '#111827' : '#ffffff';
    $cardText = $isDark ? '#f8fafc' : '#111827';
    $mutedText = $isDark ? '#cbd5e1' : '#555';
    $headingColor = $isDark ? '#e5e7eb' : '#1f3b5b';
    $borderColor = $isDark ? '#334155' : '#d1d5db';
    $tableHeadBg = $isDark ? '#1e293b' : '#0d6efd';
    $tableRowBg = $isDark ? '#0f172a' : '#f9fafb';
@endphp

<div style="
    padding: 40px 20px;
    background: {{ $pageBg }};
    min-height: calc(100vh - 90px);
">
    <div style="
        max-width: 1250px;
        margin: 0 auto;
        background: {{ $cardBg }};
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        color: {{ $cardText }};
    ">
        <h1 style="
            margin-top: 0;
            font-size: 38px;
            font-weight: 700;
            color: {{ $headingColor }};
            margin-bottom: 16px;
        ">
            Lab Transplant History
        </h1>

        <p style="
            font-size: 18px;
            color: {{ $mutedText }};
            margin-bottom: 25px;
        ">
            View all transplant records created by labs.
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

        @if($histories->isEmpty())
            <p style="font-size: 18px; color: {{ $mutedText }};">
                No transplant records found.
            </p>
        @else
            <div style="overflow-x: auto;">
                <table style="
                    width: 100%;
                    border-collapse: collapse;
                    min-width: 1100px;
                ">
                    <thead>
                        <tr style="background: {{ $tableHeadBg }}; color: #ffffff;">
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">ID</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Donor</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Receiver</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Lab</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Transplant Date</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Organ Type</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Outcome</th>
                            <th style="padding: 12px; border: 1px solid {{ $borderColor }}; text-align:left;">Condition Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr style="background: {{ $tableRowBg }};">
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">{{ $history->id }}</td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->donor->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->receiver->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->lab->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->transplant_date }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->organ_type }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->outcome }}
                                </td>
                                <td style="padding: 12px; border: 1px solid {{ $borderColor }};">
                                    {{ $history->condition_notes ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div style="margin-top: 24px;">
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