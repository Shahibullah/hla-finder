<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HLA Finder')</title>

    @php
        $theme = auth()->check() ? auth()->user()->theme : 'light';
    @endphp

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        body.light {
            background: #f5f7fb;
            color: #111;
        }

        body.dark {
            background: #121212;
            color: #f5f5f5;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }

        body.light .topbar {
            background: #ffffff;
        }

        body.dark .topbar {
            background: #1e1e1e;
        }

        .left-actions,
        .right-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
            background: #0d6efd;
            color: white;
        }

        .btn-success {
            background: #198754;
        }

        .container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            padding: 24px;
            border-radius: 14px;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        body.light .card {
            background: white;
        }

        body.dark .card {
            background: #1f1f1f;
        }
    </style>
</head>
<body class="{{ $theme }}">

    <div class="topbar">
        <div class="left-actions">
            @auth
                <form action="{{ route('theme.toggle') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn">Light / Dark</button>
                </form>
            @endauth
        </div>

        <div class="right-actions">
            <a href="{{ route('home') }}" class="btn">Home</a>

            @guest
                <a href="{{ route('login') }}" class="btn">Login</a>
                <a href="{{ route('register') }}" class="btn btn-success">Register</a>
            @else
                <span>{{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})</span>

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

    <div class="container">
        @yield('content')
    </div>

</body>
</html>