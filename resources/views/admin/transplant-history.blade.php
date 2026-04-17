@extends('layouts.main')

@section('title', 'Admin Transplant History')

@section('content')
    <div class="card">
        <h1 style="margin-top: 0;">Admin Transplant History</h1>
        <p>Admin can view all transplant records here.</p>

        <div style="margin-bottom: 20px;">
            <a href="{{ route('admin.dashboard') }}" class="btn">Back to Dashboard</a>
        </div>

        @if($histories->isEmpty())
            <p>No transplant records found.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <thead>
                        <tr style="background: #0d6efd; color: white;">
                            <th style="padding: 12px; border: 1px solid #ddd;">ID</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Donor</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Receiver</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Lab</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Transplant Date</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Organ Type</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Outcome</th>
                            <th style="padding: 12px; border: 1px solid #ddd;">Condition Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($histories as $history)
                            <tr>
                                <td style="padding: 12px; border: 1px solid #ddd;">{{ $history->id }}</td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->donor->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->receiver->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->lab->name ?? 'N/A' }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->transplant_date }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->organ_type }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->outcome }}
                                </td>
                                <td style="padding: 12px; border: 1px solid #ddd;">
                                    {{ $history->condition_notes ?? 'N/A' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection