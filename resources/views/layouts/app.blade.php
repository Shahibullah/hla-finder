<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HLA Finder</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: #f4f6f9;
        }
        nav {
            background: #0d6efd;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
        }
        .container {
            width: 90%;
            max-width: 1000px;
            margin: 30px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
        }
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

    <nav>
        <div>
            <a href="{{ route('home') }}"><strong>HLA Finder</strong></a>
        </div>

        <div>
            @auth
                <a href="{{ route('dashboard') }}">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" style="padding:6px 10px;">Logout</button>
                </form>
            @else
                <a href="{{ route('register') }}">Register</a>
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif

        @if($errors->any())
            <div class="error">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        @yield('content')
    </div>

</body>
</html>