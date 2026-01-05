<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PIXORA · Creative Frames</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,900&display=swap" rel="stylesheet" />

    <style>
        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: 
                radial-gradient(circle at 15% 20%, rgba(236,72,153,.15), transparent 40%),
                radial-gradient(circle at 85% 80%, rgba(168,85,247,.15), transparent 40%),
                linear-gradient(135deg, #fdf2f8, #f5f3ff);
            color: #1f2937;
        }

        /* LOGO ONLY TOP LEFT */
        header {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: -1px;
            color: #ec4899;
        }

        /* HERO SECTION */
        .hero {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
        }

        .hero h1 {
            font-size: clamp(3.5rem, 10vw, 6rem);
            font-weight: 900;
            margin: 0;
            line-height: 0.9;
            background: linear-gradient(to right, #ec4899, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -2px;
        }

        .hero p {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 550px;
            margin: 25px 0 45px;
            line-height: 1.6;
        }

        /* BUTTONS */
        .btn-primary {
            background: #ec4899;
            color: white;
            padding: 18px 45px;
            border-radius: 999px;
            text-decoration: none;
            font-weight: 800;
            font-size: 1.15rem;
            box-shadow: 0 10px 30px rgba(236, 72, 153, 0.3);
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(236, 72, 153, 0.4);
            filter: brightness(1.1);
        }

        footer {
            padding: 2rem;
            text-align: center;
            font-size: 0.875rem;
            color: #9ca3af;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">PIXORA ✨</div>
    </header>

    <main class="hero">
        <h1>Express<br>Yourself.</h1>
        <p>Hiasi fotomu dengan frame estetik dan bagikan momen terbaikmu lewat <b>PIXORA</b>.</p>
        
        @auth
            <a href="{{ url('/home') }}" class="btn-primary">Go to Dashboard</a>
        @else
            <a href="{{ route('login') }}" class="btn-primary">Get Started — it's free</a>
        @endauth
    </main>

    <footer>
        &copy; PIXORA STUDIO.
    </footer>

</body>
</html>