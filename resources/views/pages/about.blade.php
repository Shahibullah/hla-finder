<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - HLA Finder</title>

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
                <h1>About Us</h1>

                <p>
                    HLA Finder is a web-based platform designed to help connect organ donors,
                    recipients, administrators, and laboratories through Human Leukocyte Antigen
                    (HLA) based matching.
                </p>

                <p>
                    Our goal is to improve the process of donor-recipient matching by creating
                    a secure and easy-to-use system where users can register, manage profiles,
                    search for matches, and track transplant-related information.
                </p>

                <h2>Our Mission</h2>
                <p>
                    We aim to support better healthcare coordination by making HLA-based donor
                    matching more organized, accessible, and efficient.
                </p>

                <h2>Who Uses HLA Finder?</h2>
                <p>
                    The system is designed for four main user groups:
                </p>

                <p>
                    <strong>Admin:</strong> Manages users, labs, and system records.<br>
                    <strong>Donor:</strong> Maintains donor profile and transplant information.<br>
                    <strong>Receiver:</strong> Searches for matching donors and sends match requests.<br>
                    <strong>Lab:</strong> Updates HLA typing and manages transplant-related conditions.
                </p>

                <h2>Why HLA Matching Matters</h2>
                <p>
                    HLA compatibility plays an important role in transplantation. Better matching
                    can reduce transplant rejection risks and improve transplant success outcomes.
                </p>
            </div>
        </div>

    </div>
</body>
</html>