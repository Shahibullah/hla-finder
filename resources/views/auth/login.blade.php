<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HLA Finder</title>

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
            min-height: 100vh;
            font-family: Arial, sans-serif;
        }

        .login-page {
            min-height: 100vh;
            position: relative;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .login-page.light {
            background-image: url('{{ asset('images/login-light.png') }}');
            background-color: #eef4fb;
        }

        .login-page.dark {
            background-image: url('{{ asset('images/login-dark.png') }}');
            background-color: #05091d;
        }

        .theme-top-left {
            position: absolute;
            top: 20px;
            left: 30px;
            z-index: 30;
        }

        .theme-btn {
            padding: 10px 18px;
            border: none;
            border-radius: 24px;
            background: #0d6efd;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .theme-btn:hover {
            opacity: 0.92;
        }

        .login-page.dark .theme-btn {
            background: #2d2d2d;
            color: #fff;
        }

        .login-card {
            width: 100%;
            max-width: 510px;
            border-radius: 18px;
            padding: 34px 34px 28px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(2px);
        }

        .login-page.light .login-card {
            background: rgba(255, 255, 255, 0.88);
            color: #24446b;
            border: 1px solid rgba(190, 210, 235, 0.8);
        }

        .login-page.dark .login-card {
            background: rgba(19, 29, 73, 0.82);
            color: #f1f5ff;
            border: 1px solid rgba(83, 103, 181, 0.35);
        }

        .login-title {
            margin: 0 0 26px;
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: 700;
        }

        .form-input {
            width: 100%;
            height: 54px;
            border-radius: 10px;
            border: 1px solid;
            padding: 0 16px;
            font-size: 16px;
            outline: none;
            transition: 0.2s ease;
        }

        .login-page.light .form-input {
            background: #f5f8fc;
            border-color: #d2dcea;
            color: #2f4668;
        }

        .login-page.light .form-input::placeholder {
            color: #6c7a91;
        }

        .login-page.dark .form-input {
            background: rgba(88, 103, 168, 0.28);
            border-color: rgba(120, 140, 210, 0.35);
            color: #f4f7ff;
        }

        .login-page.dark .form-input::placeholder {
            color: #c6d0ee;
        }

        .form-input:focus {
            border-color: #5ca1ff;
            box-shadow: 0 0 0 3px rgba(92, 161, 255, 0.18);
        }

        .login-btn {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #5ea3ff, #4d86e8);
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 6px;
            transition: 0.2s ease;
        }

        .login-btn:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .forgot-wrap {
            text-align: center;
            margin-top: 18px;
        }

        .forgot-link {
            text-decoration: none;
            font-size: 16px;
        }

        .login-page.light .forgot-link {
            color: #486a97;
        }

        .login-page.dark .forgot-link {
            color: #d9e3ff;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        .bottom-links {
            margin-top: 24px;
            text-align: center;
            font-size: 15px;
            line-height: 1.8;
        }

        .login-page.light .bottom-links {
            color: #35547e;
        }

        .login-page.dark .bottom-links {
            color: #e6ecff;
        }

        .bottom-links a {
            text-decoration: none;
            font-weight: 700;
        }

        .login-page.light .bottom-links a {
            color: #1f6fe5;
        }

        .login-page.dark .bottom-links a {
            color: #9cc3ff;
        }

        .bottom-links a:hover {
            text-decoration: underline;
        }

        .alert-box {
            margin-bottom: 18px;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 14px;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            color: #14532d;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.14);
            color: #991b1b;
        }

        .login-page.dark .alert-success {
            color: #d9ffe5;
        }

        .login-page.dark .alert-error {
            color: #ffd8d8;
        }

        @media (max-width: 600px) {
            .theme-top-left {
                top: 15px;
                left: 15px;
            }

            .login-card {
                padding: 24px 20px;
            }

            .login-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-page {{ $theme }}">

        <form action="{{ route('theme.toggle') }}" method="POST" class="theme-top-left">
            @csrf
            <button type="submit" class="theme-btn">Light / Dark</button>
        </form>

        <div class="login-card">
            <h1 class="login-title">Log In</h1>

            @if(session('success'))
                <div class="alert-box alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert-box alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-input"
                        placeholder="email@example.com"
                        value="{{ old('email') }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <button type="submit" class="login-btn">Log In</button>

                <div class="forgot-wrap">
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>
            </form>

            <div class="bottom-links">
                Don’t have an account?
                <a href="{{ route('register') }}">Register</a>
                <br>
                <a href="{{ route('home') }}">Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>