<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HLA Finder</title>

    @php
        $theme = auth()->check()
            ? auth()->user()->theme
            : session('theme', 'light');
    @endphp

    <style>
        html, body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        body::before,
        body::after {
            display: none !important;
            content: none !important;
        }

        .homepage {
            min-height: 100vh;
            position: relative;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            overflow: hidden;
        }

        .homepage.light {
            background-image: url('{{ asset('images/background_light.png') }}');
            background-color: #f2f2f2;
        }

        .homepage.dark {
            background-image: url('{{ asset('images/background_dark.png') }}');
            background-color: #050512;
        }

        .theme-top-left {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 9999;
            margin: 0;
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
        }

        .homepage.dark .theme-btn {
            background: #2d2d2d;
        }

        .navbar {
            position: fixed;
            top: 20px;
            right: 30px;
            z-index: 9999;
        }

        .nav-right {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            background: rgba(0, 0, 0, 0.6);
            color: white;
            border: none;
            cursor: pointer;
        }

        .btn.register {
            background: #198754;
        }

        .overlay {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 100px 20px 40px;
        }

        .content-box {
            width: 100%;
            max-width: 680px;
            padding: 30px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.7);
        }

        .homepage.dark .content-box {
            background: rgba(0, 0, 0, 0.5);
            color: white;
        }

        .content-box h1 {
            margin: 0 0 12px;
            font-size: 48px;
        }

        .content-box p {
            margin: 0 0 18px;
        }

        .counter {
            font-size: 50px;
            font-weight: bold;
            color: #198754;
            margin-bottom: 6px;
        }

        .info-links {
            margin-top: 25px;
        }

        .info-link {
            display: inline-block;
            margin: 6px;
            padding: 10px 18px;
            border-radius: 10px;
            background: #0d6efd;
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .homepage.dark .info-link {
            background: #2d2d2d;
        }

        @media (max-width: 768px) {
            .theme-top-left {
                top: 15px;
                left: 15px;
            }

            .navbar {
                top: 15px;
                right: 15px;
            }

            .content-box h1 {
                font-size: 36px;
            }

            .counter {
                font-size: 42px;
            }

            .overlay {
                padding-top: 90px;
            }
        }
    </style>
</head>
<body>

<div class="homepage {{ $theme }}">

    <form action="{{ route('theme.toggle') }}" method="POST" class="theme-top-left">
        @csrf
        <button type="submit" class="theme-btn">Light / Dark</button>
    </form>

    <div class="navbar">
        <div class="nav-right">
            @guest
                <a href="{{ route('login') }}" class="btn">Login</a>
                <a href="{{ route('register') }}" class="btn register">Register</a>
            @else
                <a href="{{ route('logout') }}"
                   class="btn"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            @endguest
        </div>
    </div>

    <div class="overlay">
        <div class="content-box">
            <h1>Welcome to HLA Finder</h1>
            <p>Helping connect donors and receivers through HLA-based matching.</p>

            <div class="counter">{{ $activeDonorCount ?? 0 }}</div>
            <div>Active Donors Available</div>

            <div class="info-links">
                <a href="{{ route('about') }}" class="info-link">About Us</a>
                <a href="{{ route('conditions') }}" class="info-link">Conditions of Use</a>
            </div>
        </div>
    </div>

</div>

</body>
</html>