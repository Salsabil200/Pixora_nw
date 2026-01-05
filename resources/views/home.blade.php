<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PIXORA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
</head>
<body>

<header class="pixora-navbar">
    <div class="wrap">
        <div class="logo">PIXORA</div>

        <div>
            @auth
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button type="submit" class="logout-btn">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</header>

<div class="hero">
    <div class="hero-card">
        <h1>
            Not just a <span>photobooth</span><br>
            This is your <span>photo era</span>
        </h1>

        <p>
            PIXORA bikin foto kamu naik level.
            Pilih frame kece, ambil foto, edit sesuka hati,
            langsung download. No ribet. All vibes.
        </p>

        <div class="actions">
            <a href="/frames" class="primary">Get Started</a>
        </div>
    </div>
</div>

</body>
</html>