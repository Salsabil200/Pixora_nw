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
        ['name'=>'Summer','file'=>'assets/frames/season/Summer.png','desc'=>'Auto cerah! Foto jadi kelihatan fresh dan penuh vibes seru'],
        ['name'=>'Tropical','file'=>'assets/frames/season/Tropical.png','desc'=>'Vibes liburan langsung kerasa, walau cuma foto di sini'],
        ['name'=>'Pumpkin','file'=>'assets/frames/season/Pumpkin.png','desc'=>'Hangat, cozy, dan bikin momen bareng makin kerasa dekat'],
        ['name'=>'Night Christmas','file'=>'assets/frames/season/NightChristmas.png','desc'=>'Foto jadi keliatan mewah, vibes Natalnya dapet banget'],
        ['name'=>'Winter','file'=>'assets/frames/lifestyle/winter.png','desc'=>'Simple tapi bold, bikin foto kelihatan clean dan estetik'],
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
        ['name'=>'Urban Trim','file'=>'assets/frames/art/barbershop.png','desc'=>'Minimalis tapi tetep keren, cocok buat semua gaya foto'],
        ['name'=>'Blue Art','file'=>'assets/frames/art/BlueArt.png','desc'=>'Vibes kalem yang bikin foto keliatan classy tanpa effort'],
        ['name'=>'Graffiti Glow','file'=>'assets/frames/art/Free Frame Boothlab 5.png','desc'=>'Full warna, full ekspresi — foto kamu auto standout'],
        ['name'=>'Peach Royale','file'=>'assets/frames/art/clean.png','desc'=>'Soft tapi elegan, bikin foto keliatan manis dan classy'],
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
        ['name'=>'Earthy Touch','file'=>'assets/frames/nature/last.png','desc'=>'Natural vibes yang bikin foto keliatan hangat dan adem'],
        ['name'=>'Nature Luxe','file'=>'assets/frames/lifestyle/leaf.png','desc'=>'Estetik alami tapi tetep keliatan mewah'],
        ['name'=>'Golden Blush','file'=>'assets/frames/lifestyle/pink.png','desc'=>'Lembut, elegan, dan bikin foto keliatan glowing'],
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
        ['name'=>'Emerald Glam','file'=>'assets/frames/lifestyle/glam.png','desc'=>'Dark vibes tapi classy, foto kamu langsung naik level'],
        ['name'=>'Electric Purple','file'=>'assets/frames/nature/Free Frame Boothlab 3.png','desc'=>'Neon vibes on! Foto jadi kelihatan hidup dan super catchy'],
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
