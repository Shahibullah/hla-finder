<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - HLA Finder</title>

    @php
        $theme = auth()->check()
            ? auth()->user()->theme
            : session('theme', 'light');
    @endphp

    <style>
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

        .card {
            width: 100%;
            max-width: 500px;
            padding: 30px;
            border-radius: 18px;
            box-shadow: 0 10px 24px rgba(0,0,0,0.08);
            backdrop-filter: blur(3px);
        }

        .page.light .card {
            background: white;
        }

        .page.dark .card {
            background: #151d33;
        }

        h1 {
            margin-top: 0;
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
            background: #0d6efd;
            color: white;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .success {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(34, 197, 94, 0.15);
        }

        .error {
            margin-bottom: 16px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(239, 68, 68, 0.14);
        }

        .link-box {
            margin-top: 15px;
            padding: 12px;
            border-radius: 10px;
            background: rgba(13, 110, 253, 0.1);
            word-break: break-all;
        }

        .link-box a {
            color: #0d6efd;
            text-decoration: none;
            font-weight: bold;
        }

        .page.dark .link-box a {
            color: #8bb9ff;
        }

        .back-link {
            display: inline-block;
            margin-top: 14px;
            color: inherit;
            text-decoration: none;
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
            <h1>Forgot Password</h1>
            <p>Enter your email address to generate a password reset link.</p>

            @if(session('success'))
                <div class="success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <label>Email</label>
                <input type="email" name="email" class="input" value="{{ old('email') }}" required>

                <button type="submit" class="btn">Generate Reset Link</button>
            </form>

            @if(session('reset_link'))
                <div class="link-box">
                    Reset Link:
                    <br>
                    <a href="{{ session('reset_link') }}">{{ session('reset_link') }}</a>
                </div>
            @endif

            <a href="{{ route('login') }}" class="back-link">← Back to Login</a>
        </div>
    </div>
</body>
</html>