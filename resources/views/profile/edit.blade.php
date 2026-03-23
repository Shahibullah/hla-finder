<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - HLA Finder</title>

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
            max-width: 1100px;
            margin: 0 auto 30px;
        }

        .left-actions,
        .right-actions {
            display: flex;
            align-items: center;
            gap: 10px;
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

        .page.dark .btn {
            background: #243b6b;
        }

        .page.dark .btn.green {
            background: #198754;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        .card {
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }

        .page.light .card {
            background: #ffffff;
        }

        .page.dark .card {
            background: #151d33;
        }

        h1 {
            margin-top: 0;
            font-size: 34px;
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

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .form-input,
        .form-select {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            border: 1px solid #cfd8e3;
            font-size: 15px;
        }

        .page.dark .form-input,
        .page.dark .form-select {
            background: #1e2742;
            color: #fff;
            border: 1px solid #34415f;
        }

        .readonly-box {
            width: 100%;
            padding: 12px 14px;
            border-radius: 10px;
            background: #eef3f8;
            color: #444;
        }

        .page.dark .readonly-box {
            background: #1e2742;
            color: #dce6ff;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background: #198754;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
        }

        .submit-btn:hover {
            opacity: 0.95;
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

                @if(auth()->user()->role === 'donor')
                    <a href="{{ route('donor.dashboard') }}" class="btn">Dashboard</a>
                @elseif(auth()->user()->role === 'receiver')
                    <a href="{{ route('receiver.dashboard') }}" class="btn">Dashboard</a>
                @endif

                <a href="{{ route('logout') }}"
                   class="btn"
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
                <h1>Edit Profile</h1>

                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <div class="readonly-box">{{ ucfirst($user->role) }}</div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <div class="readonly-box">{{ $user->email }}</div>
                    </div>

                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ old('name', $user->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" id="dob" name="dob" class="form-input"
                               value="{{ old('dob', $user->dob) }}">
                    </div>

                    <div class="form-group">
                        <label for="sex" class="form-label">Sex</label>
                        <select id="sex" name="sex" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('sex', $user->sex) === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $user->sex) === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Others" {{ old('sex', $user->sex) === 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone_no" class="form-label">Phone Number</label>
                        <input type="text" id="phone_no" name="phone_no" class="form-input"
                               value="{{ old('phone_no', $user->phone_no) }}">
                    </div>

                    <div class="form-group">
                        <label for="address_by_divisions" class="form-label">Address</label>
                        <input type="text" id="address_by_divisions" name="address_by_divisions" class="form-input"
                               value="{{ old('address_by_divisions', $user->address_by_divisions) }}">
                    </div>

                    <button type="submit" class="submit-btn">Update Profile</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>