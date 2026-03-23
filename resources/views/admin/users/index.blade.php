<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - HLA Finder</title>

    @php
        $theme = auth()->check()
            ? auth()->user()->theme
            : session('theme', 'light');
    @endphp

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            min-height: 100vh;
            padding: 30px 20px;
        }

        .page.light {
            background: #f4f8fc;
            color: #1e2d3d;
        }

        .page.dark {
            background: #0b1020;
            color: #eef3ff;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto 30px;
            gap: 10px;
            flex-wrap: wrap;
        }

        .left-actions,
        .right-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            background: #0d6efd;
            color: #fff;
        }

        .btn.green {
            background: #198754;
        }

        .btn.red {
            background: #dc3545;
        }

        .btn.gray {
            background: #6c757d;
        }

        .page.dark .btn {
            background: #243b6b;
        }

        .page.dark .btn.green {
            background: #198754;
        }

        .page.dark .btn.red {
            background: #dc3545;
        }

        .page.dark .btn.gray {
            background: #6c757d;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            padding: 24px;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .page.light .card {
            background: #ffffff;
        }

        .page.dark .card {
            background: #151d33;
        }

        h1, h2 {
            margin-top: 0;
        }

        .alert-success {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(34, 197, 94, 0.15);
            color: #14532d;
        }

        .page.dark .alert-success {
            color: #d9ffe5;
        }

        .alert-error {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.14);
            color: #991b1b;
        }

        .page.dark .alert-error {
            color: #ffd8d8;
        }

        .search-form {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .input {
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #cfd8e3;
            font-size: 15px;
            min-width: 280px;
            flex: 1;
        }

        .page.dark .input {
            background: #1e2742;
            color: #fff;
            border: 1px solid #34415f;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 14px 10px;
            text-align: left;
            vertical-align: top;
            border-bottom: 1px solid #dbe4f0;
        }

        .page.dark th,
        .page.dark td {
            border-bottom: 1px solid #2c3957;
        }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .badge.admin {
            background: rgba(108, 117, 125, 0.18);
            color: #495057;
        }

        .badge.lab {
            background: rgba(111, 66, 193, 0.18);
            color: #6f42c1;
        }

        .badge.donor {
            background: rgba(25, 135, 84, 0.15);
            color: #198754;
        }

        .badge.receiver {
            background: rgba(13, 110, 253, 0.15);
            color: #0d6efd;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
        }

        .status-badge.active {
            background: rgba(25, 135, 84, 0.15);
            color: #198754;
        }

        .status-badge.inactive {
            background: rgba(220, 53, 69, 0.15);
            color: #dc3545;
        }

        .action-form {
            display: inline-block;
            margin-right: 8px;
        }

        @media (max-width: 900px) {
            table, thead, tbody, tr, th, td {
                display: block;
                width: 100%;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 18px;
                padding-bottom: 14px;
                border-bottom: 1px solid #dbe4f0;
            }

            .page.dark tr {
                border-bottom: 1px solid #2c3957;
            }

            td {
                border: none;
                padding: 8px 0;
            }

            td::before {
                content: attr(data-label);
                display: block;
                font-weight: bold;
                margin-bottom: 6px;
            }
        }
    </style>
</head>
<body>
    <div class="page {{ $theme }}">

        <div class="topbar">
            <div class="left-actions">
                <form action="{{ route('theme.toggle') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn">Light / Dark</button>
                </form>
            </div>

            <div class="right-actions">
                <a href="{{ route('home') }}" class="btn">Home</a>
                <a href="{{ route('admin.dashboard') }}" class="btn">Dashboard</a>

                <a href="{{ route('logout') }}"
                   class="btn red"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>

        <div class="container">
            <div class="card">
                <h1>Manage User Accounts</h1>
                <p>Admin can activate or deactivate donor, receiver, and lab accounts from this page.</p>

                @if(session('success'))
                    <div class="alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.users.index') }}" class="search-form">
                    <input
                        type="text"
                        name="search"
                        class="input"
                        placeholder="Search by name, email, or role"
                        value="{{ $search }}"
                    >
                    <button type="submit" class="btn">Search</button>
                    <a href="{{ route('admin.users.index') }}" class="btn green">Reset</a>
                </form>
            </div>

            <div class="card">
                <h2>All Users</h2>

                @if($users->isEmpty())
                    <p>No users found.</p>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td data-label="Name">{{ $user->name }}</td>
                                    <td data-label="Role">
                                        <span class="badge {{ $user->role }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td data-label="Email">{{ $user->email }}</td>
                                    <td data-label="Status">
                                        <span class="status-badge {{ $user->status }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td data-label="Action">
                                        @if($user->role === 'admin')
                                            <span class="btn gray" style="cursor:not-allowed;">No Action</span>
                                        @elseif($user->status === 'active')
                                            <form action="{{ route('admin.users.deactivate', $user->id) }}" method="POST" class="action-form">
                                                @csrf
                                                <button type="submit" class="btn red">Deactivate</button>
                                            </form>
                                        @else
                                            <form action="{{ route('admin.users.activate', $user->id) }}" method="POST" class="action-form">
                                                @csrf
                                                <button type="submit" class="btn green">Activate</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>

    </div>
</body>
</html>