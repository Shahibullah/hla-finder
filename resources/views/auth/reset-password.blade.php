<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - HLA Finder</title>

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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .page.light {
            background-image: url('{{ asset('images/ForgotPassword_light.png') }}');
            background-color: #eef4fb;
            color: #1d3557;
        }

        .page.dark {
            background-image: url('{{ asset('images/ForgotPassword_dark.png') }}');
            background-color: #0b1020;
            color: #eef3ff;
        }

        .theme-top-left {
            position: absolute;
            top: 20px;
            left: 30px;
            z-index: 20;
        }

        .theme-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 24px;
            background: #0d6efd;
            color: #fff;
            font-weight: bold;
            cursor: pointer;
        }

        .page.dark .theme-btn {
            background: #2d2d2d;
            color: #fff;
        }

        .card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            backdrop-filter: blur(3px);
        }

        .page.light .card {
            background: rgba(255, 255, 255, 0.88);
        }

        .page.dark .card {
            background: rgba(21, 29, 51, 0.88);
        }

        h1 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 30px;
        }

        p {
            margin-top: 0;
            margin-bottom: 20px;
            line-height: 1.6;
        }

        .input {
            width: 100%;
            padding: 12px 14px;
            margin-top: 8px;
            margin-bottom: 18px;
            border-radius: 10px;
            border: 1px solid #cfd8e3;
            font-size: 15px;
        }

        .page.dark .input {
            background: #1e2742;
            color: white;
            border: 1px solid #34415f;
        }

        .btn {
            display: inline-block;
            padding: 12px 16px;
            border: none;
            border-radius: 10px;
            background: #198754;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .btn:hover,
        .theme-btn:hover {
            opacity: 0.94;
        }

        .error {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.14);
            color: #991b1b;
        }

        .page.dark .error {
            color: #ffd8d8;
        }

        .back-link {
            display: inline-block;
            margin-top: 14px;
            color: inherit;
            text-decoration: none;
            font-weight: 600;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .theme-top-left {
                top: 15px;
                left: 15px;
            }

            .card {
                padding: 22px 18px;
            }

            h1 {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="page {{ $theme }}">
        <form action="{{ route('theme.toggle') }}" method="POST" class="theme-top-left">
            @csrf
            <button type="submit" class="theme-btn">Light / Dark</button>
        </form>

        <div class="card">
            <h1>Reset Password</h1>
            <p>Enter your new password below.</p>

            @if($errors->any())
                <div class="error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <label>Email</label>
                <input type="email" name="email" class="input" value="{{ old('email', $email) }}" required>

                <label>New Password</label>
                <input type="password" name="password" class="input" required>

                <label>Confirm Password</label>
                <input type="password" name="password_confirmation" class="input" required>

                <button type="submit" class="btn">Reset Password</button>
            </form>

            <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
        </div>
    </div>
</body>
</html>