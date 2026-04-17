<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'HLA Finder')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body style="
    margin: 0;
    font-family: Arial, Helvetica, sans-serif;
    background: {{ session('theme', 'light') === 'dark' ? '#0b0b0b' : '#e9edf3' }};
    color: {{ session('theme', 'light') === 'dark' ? '#f8fafc' : '#111827' }};
">

    <nav style="
        background: {{ session('theme', 'light') === 'dark' ? '#171717' : '#1f2937' }};
        padding: 16px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    ">
        <div>
            <form action="{{ route('theme.toggle') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="
                    background:#2563eb;
                    color:#fff;
                    border:none;
                    border-radius:10px;
                    padding:8px 14px;
                    font-weight:700;
                    cursor:pointer;
                ">
                    Light / Dark
                </button>
            </form>
        </div>

        <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('home') }}" style="
                background:#2563eb;
                color:#fff;
                text-decoration:none;
                padding:8px 14px;
                border-radius:10px;
                font-weight:700;
            ">
                Home
            </a>

            @auth
                <span style="color:#fff; font-weight:600;">
                    {{ auth()->user()->name }} ({{ ucfirst(auth()->user()->role) }})
                </span>

                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="
                        background:#2563eb;
                        color:#fff;
                        border:none;
                        border-radius:10px;
                        padding:8px 14px;
                        font-weight:700;
                        cursor:pointer;
                    ">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" style="
                    color:#fff;
                    text-decoration:none;
                    font-weight:700;
                ">
                    Log in
                </a>

                <a href="{{ route('register') }}" style="
                    color:#fff;
                    text-decoration:none;
                    border:1px solid rgba(255,255,255,0.35);
                    padding:8px 14px;
                    border-radius:8px;
                    font-weight:700;
                ">
                    Register
                </a>
            @endauth
        </div>
    </nav>

    @yield('content')

</body>
</html>