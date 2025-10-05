<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GeoCetak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            color: #1a202c;
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
        }

        .auth-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 450px;
            position: relative;
            overflow: hidden;
        }

        .auth-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-logo h1 {
            font-size: 32px;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .auth-logo p {
            color: #718096;
            font-size: 16px;
        }

        .auth-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-title h2 {
            font-size: 28px;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .auth-title p {
            color: #718096;
            font-size: 14px;
        }

        .form-floating {
            position: relative;
            margin-bottom: 20px;
        }

        .form-floating input {
            width: 100%;
            padding: 15px 12px 15px 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #fafafa;
        }

        .form-floating input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-floating label {
            position: absolute;
            top: 15px;
            left: 12px;
            color: #718096;
            font-size: 16px;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
        }

        .form-floating input:focus + label,
        .form-floating input:not(:placeholder-shown) + label {
            top: -8px;
            left: 8px;
            font-size: 12px;
            color: #667eea;
            background: white;
            padding: 0 4px;
        }

        .auth-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .auth-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .auth-btn:active {
            transform: translateY(0);
        }

        .auth-link {
            text-align: center;
            margin-top: 20px;
        }

        .auth-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .auth-link a:hover {
            color: #764ba2;
            text-decoration: underline;
        }


        .alert-auth {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-auth.success {
            background: #f0fff4;
            border: 1px solid #c6f6d5;
            color: #22543d;
        }

        .alert-auth.error {
            background: #fed7d7;
            border: 1px solid #feb2b2;
            color: #742a2a;
        }

    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-logo">
                <h1>GeoCetak</h1>
                <p>Dashboard Guru</p>
            </div>

            <div class="auth-title">
                <h2>Buat Akun Baru</h2>
                <p>Daftar sebagai guru GeoCetak</p>
            </div>

            @if ($errors->any())
                <div class="alert-auth error">
                    @foreach ($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-floating">
                    <input type="text" id="full_name" name="full_name" value="{{ old('full_name') }}" placeholder=" " required autofocus>
                    <label for="full_name">Nama Lengkap</label>
                </div>

                <div class="form-floating">
                    <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder=" " required>
                    <label for="email">Email</label>
                </div>

                <div class="form-floating">
                    <input type="password" id="password" name="password" placeholder=" " required minlength="6">
                    <label for="password">Password</label>
                </div>

                <div class="form-floating">
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder=" " required>
                    <label for="password_confirmation">Konfirmasi Password</label>
                </div>



                <button type="submit" class="auth-btn">
                    Daftar
                </button>
            </form>

            <div class="auth-link">
                <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
            </div>
        </div>
    </div>

</body>
</html>
