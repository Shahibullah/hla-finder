<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions of Use - HLA Finder</title>

    @php
        $theme = auth()->check()
            ? auth()->user()->theme
            : session('theme', 'light');
    @endphp

    <style>
        html, body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        .page {
            min-height: 100vh;
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
            padding: 30px 20px;
        }

        .page.light {
            background-image: url('{{ asset('images/about_light.png') }}');
            background-color: #f4f8fc;
            color: #1e2d3d;
        }

        .page.dark {
            background-image: url('{{ asset('images/about_dark.png') }}');
            background-color: #0b1020;
            color: #eef3ff;
        }

        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1100px;
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

        .page.dark .btn {
            background: #243b6b;
        }

        .page.dark .btn.green {
            background: #198754;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            padding: 30px;
            border-radius: 18px;
            line-height: 1.8;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
            backdrop-filter: blur(2px);
        }

        .page.light .card {
            background: rgba(255, 255, 255, 0.88);
        }

        .page.dark .card {
            background: rgba(21, 29, 51, 0.82);
        }

        h1 {
            margin-top: 0;
            font-size: 38px;
        }

        h2 {
            margin-top: 28px;
            font-size: 24px;
        }

        p {
            font-size: 17px;
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

                @guest
                    <a href="{{ route('login') }}" class="btn">Login</a>
                    <a href="{{ route('register') }}" class="btn green">Register</a>
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

        <div class="container">
            <div class="card">
                <h1>Conditions of Use</h1>

                <p>
                    Welcome to HLA Finder. By accessing and using this system, you agree to use
                    it responsibly and only for lawful and ethical purposes related to donor,
                    receiver, laboratory, and transplant information management.
                </p>

                <h2>1. Informational Purpose</h2>
                <p>
                    HLA Finder is intended to support HLA-based matching and transplant information
                    management. It does not replace professional medical advice, diagnosis, or treatment.
                </p>

                <h2>2. User Responsibility</h2>
                <p>
                    Users are responsible for providing accurate and updated information during
                    registration and profile management. False or misleading information may affect
                    matching results and system reliability.
                </p>

                <h2>3. Account Security</h2>
                <p>
                    Users must keep their login credentials confidential. Any activity performed
                    through an account is the responsibility of the account owner.
                </p>

                <h2>4. Authorized Use</h2>
                <p>
                    Admins, donors, receivers, and labs may only access the system features permitted
                    for their assigned roles. Unauthorized access attempts are strictly prohibited.
                </p>

                <h2>5. Privacy and Sensitive Data</h2>
                <p>
                    HLA-related and transplant-related data are sensitive. Users must not misuse,
                    copy, or share another person’s information without proper authorization.
                </p>

                <h2>6. Service Changes</h2>
                <p>
                    The system may be updated, modified, or temporarily unavailable for maintenance
                    or improvements without prior notice.
                </p>

                <h2>7. Acceptance</h2>
                <p>
                    By continuing to use HLA Finder, you acknowledge and accept these conditions of use.
                </p>
            </div>
        </div>

    </div>
</body>
</html>