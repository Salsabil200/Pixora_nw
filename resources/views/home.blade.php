<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PIXORA</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
/* RESET */
*{
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;
}

/* BODY */
body{
    margin:0;
    min-height:100vh;
    background:
        radial-gradient(circle at 15% 20%, rgba(236,72,153,.25), transparent 45%),
        radial-gradient(circle at 85% 80%, rgba(168,85,247,.25), transparent 45%),
        linear-gradient(135deg,#fde2f3,#fbcfe8,#e0e7ff);
    display:flex;
    flex-direction:column;
}

/* ===== NAVBAR (UPDATED – SAMA DENGAN FRAMES) ===== */
header.pixora-navbar{
    background:linear-gradient(135deg,#fbc2eb,#a6c1ee);
    padding:20px;
}
header.pixora-navbar .wrap{
    max-width:1100px;
    margin:auto;
    display:flex;
    align-items:center;
    justify-content:space-between;
}
header.pixora-navbar .logo{
    font-weight:900;
    font-size:22px;
    letter-spacing:1px;
    color:#fff;
}
header.pixora-navbar a{
    text-decoration:none;
    font-weight:700;
    margin-left:18px;
    color:#fff;
}
header.pixora-navbar button{
    background:none;
    border:none;
    color:#fff;
    font-weight:700;
    cursor:pointer;
    margin-left:18px;
}

/* HERO */
.hero{
    flex:1;
    display:flex;
    align-items:center;
    justify-content:center;
}
.hero-card{
    background:white;
    padding:45px;
    border-radius:30px;
    max-width:720px;
    box-shadow:0 25px 60px rgba(0,0,0,.15);
}
.hero-card h1{
    margin:0;
    font-size:48px;
    line-height:1.1;
}
.hero-card span{
    color:#ec4899;
}
.hero-card p{
    margin-top:15px;
    font-size:18px;
    color:#555;
}
.actions{
    margin-top:25px;
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}
.actions a{
    text-decoration:none;
    font-weight:800;
    padding:14px 26px;
    border-radius:999px;
}
.actions .primary{
    background:#ec4899;
    color:white;
}
</style>
</head>
<body>

<!-- NAVBAR -->
<header class="pixora-navbar">
    <div class="wrap">
        <div class="logo">PIXORA</div>

        <div>
            <a href="/">Home</a>
            <a href="/frames">Frames</a>

            @guest
                <a href="/login">Login</a>
            @endguest

            @auth
                <form method="POST" action="/logout" style="display:inline;">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            @endauth
        </div>
    </div>
</header>

<!-- HERO -->
<div class="hero">
    <div class="hero-card">
        <h1>
            Not just a <span>photobooth</span><br>
            This is your <span>photo era</span>
        </h1>

        <p>
            PIXORA bikin foto kamu naik level.
            Pilih frame kece, ambil 6 foto, edit sesuka hati,
            langsung download. No ribet. All vibes.
        </p>

        <div class="actions">
            <a href="/frames" class="primary">Get Started</a>
        </div>
    </div>
</div>

</body>
</html>
