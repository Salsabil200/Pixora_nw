<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login · PIXORA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800" rel="stylesheet" />

    <style>
        /* RESET & BASE */
        * {
            box-sizing: border-box;
            font-family: 'Figtree', sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            /* Background yang lebih dinamis */
            background: 
                radial-gradient(circle at 10% 10%, rgba(236,72,153,0.15), transparent 30%),
                radial-gradient(circle at 90% 90%, rgba(168,85,247,0.15), transparent 30%),
                #f8fafc;
        }

        /* CARD UPGRADE */
        .card {
            width: 100%;
            max-width: 440px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08), 0 0 0 1px rgba(0,0,0,0.02);
            border: 1px solid rgba(255, 255, 255, 0.7);
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* LOGO & TITLE */
        .header-box {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-box h2 {
            margin: 0;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -1px;
            background: linear-gradient(135deg, #ec4899, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .header-box p {
            margin: 10px 0 0;
            color: #64748b;
            font-size: 16px;
        }

        /* INPUTS */
        .input-group {
            margin-bottom: 16px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            border-radius: 16px;
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            font-size: 15px;
            transition: all 0.3s ease;
            color: #1e293b;
        }

        input:focus {
            outline: none;
            border-color: #ec4899;
            background: white;
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        /* BUTTONS */
        button {
            width: 100%;
            margin-top: 10px;
            padding: 16px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            background: #1e293b; /* Dark Mode Style */
            color: white;
            transition: all 0.3s ease;
        }

        button:hover {
            background: #0f172a;
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        /* MODERN GOOGLE BUTTON */
        .btn-google {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 14px;
            background: white;
            color: #334155;
            border: 2px solid #f1f5f9;
            border-radius: 16px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-google:hover {
            background: #f8fafc;
            border-color: #e2e8f0;
            transform: translateY(-2px);
        }

        .btn-google img {
            width: 20px;
            height: 20px;
        }

        /* DIVIDER */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #f1f5f9;
        }

        .divider:not(:empty)::before { margin-right: 1.5em; }
        .divider:not(:empty)::after { margin-left: 1.5em; }

        /* FOOTER */
        .footer {
            text-align: center;
            margin-top: 25px;
            font-size: 15px;
            color: #64748b;
        }

        .footer a {
            color: #ec4899;
            text-decoration: none;
            font-weight: 700;
            margin-left: 5px;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        /* ERROR MESSAGE */
        .error {
            background: #fff1f2;
            color: #e11d48;
            padding: 14px;
            border-radius: 16px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
            border: 1px solid #ffe4e6;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="header-box">
        <h2>PIXORA ✨</h2>
        <p>Log in to your creative space</p>
    </div>

    @if ($errors->any())
        <div class="error">
            ⚠️ {{ $errors->first() }}
        </div>
    @endif

    {{-- TOMBOL GOOGLE DI ATAS (Lebih Tren Sekarang) --}}
    <a href="{{ url('auth/google') }}" class="btn-google">
        <img src="https://fonts.gstatic.com/s/i/productlogos/googleg/v6/24px.svg" alt="Google">
        Continue with Google
    </a>

    <div class="divider">or use email</div>

    <form method="POST" action="/login">
        @csrf
        <div class="input-group">
            <input type="email" name="email" placeholder="Email address" value="{{ old('email') }}" required>
        </div>
        <div class="input-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <button type="submit">Log In</button>
    </form>

    <div class="footer">
        Don't have an account?
        <a href="/register">Create one</a>
    </div>
</div>

</body>
</html>