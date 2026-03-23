<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - HLA Finder</title>

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

        .register-page {
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

        .register-page.light {
            background-image: url('{{ asset('images/login-light.png') }}');
            background-color: #eef4fb;
        }

        .register-page.dark {
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
        }

        .register-page.dark .theme-btn {
            background: #2d2d2d;
            color: #fff;
        }

        .register-card {
            width: 100%;
            max-width: 620px;
            border-radius: 18px;
            padding: 34px 34px 28px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(2px);
        }

        .register-page.light .register-card {
            background: rgba(255, 255, 255, 0.88);
            color: #24446b;
            border: 1px solid rgba(190, 210, 235, 0.8);
        }

        .register-page.dark .register-card {
            background: rgba(19, 29, 73, 0.82);
            color: #f1f5ff;
            border: 1px solid rgba(83, 103, 181, 0.35);
        }

        .register-title {
            margin: 0 0 26px;
            text-align: center;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.4px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .grid-full {
            grid-column: 1 / -1;
        }

        .form-group {
            margin-bottom: 0;
        }

        .form-label {
            display: block;
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: 700;
        }

        .form-input,
        .form-select {
            width: 100%;
            height: 54px;
            border-radius: 10px;
            border: 1px solid;
            padding: 0 16px;
            font-size: 16px;
            outline: none;
            transition: 0.2s ease;
        }

        .register-page.light .form-input,
        .register-page.light .form-select {
            background: #f5f8fc;
            border-color: #d2dcea;
            color: #2f4668;
        }

        .register-page.light .form-input::placeholder {
            color: #6c7a91;
        }

        .register-page.dark .form-input,
        .register-page.dark .form-select {
            background: rgba(88, 103, 168, 0.28);
            border-color: rgba(120, 140, 210, 0.35);
            color: #f4f7ff;
        }

        .register-page.dark .form-input::placeholder {
            color: #c6d0ee;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #5ca1ff;
            box-shadow: 0 0 0 3px rgba(92, 161, 255, 0.18);
        }

        .register-btn {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(90deg, #5ea3ff, #4d86e8);
            color: white;
            font-size: 18px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 22px;
            transition: 0.2s ease;
        }

        .register-btn:hover {
            transform: translateY(-1px);
            opacity: 0.96;
        }

        .bottom-links {
            margin-top: 24px;
            text-align: center;
            font-size: 15px;
            line-height: 1.8;
        }

        .register-page.light .bottom-links {
            color: #35547e;
        }

        .register-page.dark .bottom-links {
            color: #e6ecff;
        }

        .bottom-links a {
            text-decoration: none;
            font-weight: 700;
        }

        .register-page.light .bottom-links a {
            color: #1f6fe5;
        }

        .register-page.dark .bottom-links a {
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

        .alert-error {
            background: rgba(239, 68, 68, 0.14);
            color: #991b1b;
        }

        .register-page.dark .alert-error {
            color: #ffd8d8;
        }

        @media (max-width: 700px) {
            .theme-top-left {
                top: 15px;
                left: 15px;
            }

            .register-card {
                padding: 24px 20px;
            }

            .register-title {
                font-size: 24px;
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-page {{ $theme }}">

        <form action="{{ route('theme.toggle') }}" method="POST" class="theme-top-left">
            @csrf
            <button type="submit" class="theme-btn">Light / Dark</button>
        </form>

        <div class="register-card">
            <h1 class="register-title">Create Account</h1>

            @if($errors->any())
                <div class="alert-box alert-error">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <div class="grid">
                    <div class="form-group grid-full">
                        <label for="role" class="form-label">Role</label>
                        <select id="role" name="role" class="form-select" required>
                            <option value="">Select role</option>
                            <option value="donor" {{ old('role') === 'donor' ? 'selected' : '' }}>Donor</option>
                            <option value="receiver" {{ old('role') === 'receiver' ? 'selected' : '' }}>Receiver</option>
                            <option value="lab" {{ old('role') === 'lab' ? 'selected' : '' }}>Lab</option>
                        </select>
                    </div>

                    <div class="form-group grid-full">
                        <label for="name" class="form-label">Full Name</label>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            class="form-input"
                            placeholder="Enter full name"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="form-group" id="dob-group">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input
                            id="dob"
                            type="date"
                            name="dob"
                            class="form-input"
                            value="{{ old('dob') }}"
                        >
                    </div>

                    <div class="form-group" id="sex-group">
                        <label for="sex" class="form-label">Sex</label>
                        <select id="sex" name="sex" class="form-select">
                            <option value="">Select</option>
                            <option value="Male" {{ old('sex') === 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex') === 'Female' ? 'selected' : '' }}>Female</option>
                            <option value="Others" {{ old('sex') === 'Others' ? 'selected' : '' }}>Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="phone_no" class="form-label">Phone Number</label>
                        <input
                            id="phone_no"
                            type="text"
                            name="phone_no"
                            class="form-input"
                            placeholder="Enter phone number"
                            value="{{ old('phone_no') }}"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="address_by_divisions" class="form-label">Address by Division</label>
                        <select id="address_by_divisions" name="address_by_divisions" class="form-select" required>
                            <option value="">Select Division</option>
                            <option value="Barishal" {{ old('address_by_divisions') === 'Barishal' ? 'selected' : '' }}>Barishal</option>
                            <option value="Chattogram" {{ old('address_by_divisions') === 'Chattogram' ? 'selected' : '' }}>Chattogram</option>
                            <option value="Dhaka" {{ old('address_by_divisions') === 'Dhaka' ? 'selected' : '' }}>Dhaka</option>
                            <option value="Khulna" {{ old('address_by_divisions') === 'Khulna' ? 'selected' : '' }}>Khulna</option>
                            <option value="Mymensingh" {{ old('address_by_divisions') === 'Mymensingh' ? 'selected' : '' }}>Mymensingh</option>
                            <option value="Rajshahi" {{ old('address_by_divisions') === 'Rajshahi' ? 'selected' : '' }}>Rajshahi</option>
                            <option value="Rangpur" {{ old('address_by_divisions') === 'Rangpur' ? 'selected' : '' }}>Rangpur</option>
                            <option value="Sylhet" {{ old('address_by_divisions') === 'Sylhet' ? 'selected' : '' }}>Sylhet</option>
                        </select>
                    </div>

                    <div class="form-group grid-full">
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
                            placeholder="Enter password"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            class="form-input"
                            placeholder="Confirm password"
                            required
                        >
                    </div>

                    <div class="form-group grid-full" id="lab-name-group" style="display: none;">
                        <label for="lab_name" class="form-label">Lab Name</label>
                        <input
                            id="lab_name"
                            type="text"
                            name="lab_name"
                            class="form-input"
                            placeholder="Enter lab name"
                            value="{{ old('lab_name') }}"
                        >
                    </div>

                    <div class="form-group grid-full" id="lab-address-group" style="display: none;">
                        <label for="lab_address" class="form-label">Lab Address</label>
                        <input
                            id="lab_address"
                            type="text"
                            name="lab_address"
                            class="form-input"
                            placeholder="Enter lab address"
                            value="{{ old('lab_address') }}"
                        >
                    </div>
                </div>

                <button type="submit" class="register-btn">Register</button>
            </form>

            <div class="bottom-links">
                Already have an account?
                <a href="{{ route('login') }}">Login</a>
                <br>
                <a href="{{ route('home') }}">Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        function toggleLabFields() {
            const role = document.getElementById('role').value;
            const labNameGroup = document.getElementById('lab-name-group');
            const labAddressGroup = document.getElementById('lab-address-group');
            const dobGroup = document.getElementById('dob-group');
            const sexGroup = document.getElementById('sex-group');

            if (role === 'lab') {
                labNameGroup.style.display = 'block';
                labAddressGroup.style.display = 'block';
                dobGroup.style.display = 'none';
                sexGroup.style.display = 'none';
            } else {
                labNameGroup.style.display = 'none';
                labAddressGroup.style.display = 'none';
                dobGroup.style.display = 'block';
                sexGroup.style.display = 'block';
            }
        }

        document.getElementById('role').addEventListener('change', toggleLabFields);
        window.addEventListener('load', toggleLabFields);
    </script>
</body>
</html>