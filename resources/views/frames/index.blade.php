<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXORA – Choose Your Frame</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
</head>

<body>

<header>
    <h1>PIXORA</h1>
    <nav>
        @auth
            <form method="POST" action="/logout" style="display:inline;">
                @csrf
                <button type="submit" style="font-family: inherit;">Logout</button>
            </form>
        @else
            <a href="/login">Login</a>
        @endauth
    </nav>
</header>

{{-- ================= HERO SECTION ================= --}}
<div class="hero">
    <h2>Capture Your Best Moments ✨</h2>
    <p>Ekspresikan dirimu dengan koleksi frame estetik dan eksklusif kami.</p>
</div>

<div class="container">

    {{-- ================= SEASON SERIES ================= --}}
    <h2 class="section-title">Season Series</h2>
    <p class="section-desc">Frame bertema musim dan perayaan spesial.</p>

    @php
    $season = [
        ['name'=>'Summer','file'=>'assets/frames/season/Summer.png','desc'=>'Cerah, hangat, dan penuh energi'],
        ['name'=>'Tropical','file'=>'assets/frames/season/Tropical.png','desc'=>'Eksotis dan segar ala tropis'],
        ['name'=>'Pumpkin','file'=>'assets/frames/season/Pumpkin.png','desc'=>'Hangat dan cozy bernuansa gugur'],
        ['name'=>'Night Christmas','file'=>'assets/frames/season/NightChristmas.png','desc'=>'Malam Natal yang mewah dan magis'],
    ];
    @endphp

    <div class="grid">
    @foreach($season as $f)
    <div class="card">
        <div class="thumb">
            <img src="{{ asset($f['file']) }}" alt="{{ $f['name'] }}">
        </div>
        <h4>{{ $f['name'] }}</h4>
        <p>{{ $f['desc'] }}</p>
        <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
    </div>
    @endforeach
    </div>

    {{-- ================= ART SERIES ================= --}}
    <h2 class="section-title">Art Series</h2>
    <p class="section-desc">Sentuhan artistik untuk foto estetik.</p>

    @php
    $art = [
        ['name'=>'barbershop','file'=>'assets/frames/art/barbershop.png','desc'=>'Minimalis, rapi, dan modern'],
        ['name'=>'Blue Art','file'=>'assets/frames/art/BlueArt.png','desc'=>'Biru elegan yang menenangkan'],
        ['name'=>'Free Frame Boothlab 5','file'=>'assets/frames/art/Free Frame Boothlab 5.png','desc'=>'Lembut, manis, dan estetik'],
        ['name'=>'clean','file'=>'assets/frames/art/clean.png','desc'=>'Lembut, manis, dan estetik'],
    ];
    @endphp

    <div class="grid">
    @foreach($art as $f)
    <div class="card">
        <div class="thumb">
            <img src="{{ asset($f['file']) }}" alt="{{ $f['name'] }}">
        </div>
        <h4>{{ $f['name'] }}</h4>
        <p>{{ $f['desc'] }}</p>
        <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
    </div>
    @endforeach
    </div>

    {{-- ================= NATURE SERIES ================= --}}
    <h2 class="section-title">Nature Series</h2>
    <p class="section-desc">Nuansa alam yang segar dan natural.</p>

    @php
    $nature = [
        ['name'=>'Free Frame Boothlab 3','file'=>'assets/frames/nature/Free Frame Boothlab 3.png','desc'=>'Tropis ceria dengan sentuhan unik'],
        ['name'=>'last','file'=>'assets/frames/nature/last.png','desc'=>'Tropis ceria dengan sentuhan unik'],
    ];
    @endphp

    <div class="grid">
    @foreach($nature as $f)
    <div class="card">
        <div class="thumb">
            <img src="{{ asset($f['file']) }}" alt="{{ $f['name'] }}">
        </div>
        <h4>{{ $f['name'] }}</h4>
        <p>{{ $f['desc'] }}</p>
        <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
    </div>
    @endforeach
    </div>

    {{-- ================= LIFESTYLE SERIES ================= --}}
    <h2 class="section-title">Lifestyle Series</h2>
    <p class="section-desc">Frame untuk gaya hidup modern.</p>

    @php
    $lifestyle = [
        ['name'=>'glam','file'=>'assets/frames/lifestyle/glam.png','desc'=>'Biru kasual untuk sehari-hari'],
        ['name'=>'leaf','file'=>'assets/frames/lifestyle/leaf.png','desc'=>'Mewah dan berkelas'],
        ['name'=>'pink','file'=>'assets/frames/lifestyle/pink.png','desc'=>'Bold dengan vibe malam'],
        ['name'=>'winter','file'=>'assets/frames/lifestyle/winter.png','desc'=>'Bold dengan vibe malam'],
    ];
    @endphp

    <div class="grid">
    @foreach($lifestyle as $f)
    <div class="card">
        <div class="thumb">
            <img src="{{ asset($f['file']) }}" alt="{{ $f['name'] }}">
        </div>
        <h4>{{ $f['name'] }}</h4>
        <p>{{ $f['desc'] }}</p>
        <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
    </div>
    @endforeach
    </div>

</div>

<footer>
    <strong>PIXORA STUDIO</strong><br>
    <p style="margin-top: 5px; opacity: 0.7;">© 2026 Pixora Experience. All rights reserved.</p>
    <p style="font-size: 18px; margin-top: 10px;">Capture your moments beautifully ✨</p>
</footer>

</body>
</html>
