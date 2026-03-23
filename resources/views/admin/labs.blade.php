@extends('layouts.app')

@section('content')

<h2>Manage Labs</h2>

@if(session('success'))
<p style="color:green; font-weight:bold;">
    {{ session('success') }}
</p>
@endif

<table border="1" cellpadding="10" cellspacing="0" width="100%">

<tr style="background:#f2f2f2;">
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Address</th>
    <th>Status</th>
    <th>Action</th>
</tr>

@forelse($labs as $lab)

<tr>
    <td>{{ $lab->id }}</td>
    <td>{{ $lab->name }}</td>
    <td>{{ $lab->email }}</td>
    <td>{{ $lab->phone_no }}</td>

    <!-- Address from address_by_divisions -->
    <td>{{ $lab->address_by_divisions }}</td>

    <td>
        @if($lab->status == 'active')
            <span style="color:green;font-weight:bold;">Active</span>
        @else
            <span style="color:red;font-weight:bold;">Inactive</span>
        @endif
    </td>

    <td>

        @if($lab->status == 'inactive')

        <form method="POST" action="{{ route('admin.activateLab',$lab->id) }}">
            @csrf
            <button type="submit">Activate</button>
        </form>

        @else

        <form method="POST" action="{{ route('admin.deactivateLab',$lab->id) }}">
            @csrf
            <button type="submit">Deactivate</button>
        </form>

        @endif

    </td>

</tr>

@empty

<tr>
    <td colspan="7" style="text-align:center;">
        No labs registered yet.
    </td>
</tr>

@endforelse

</table>

@endsection