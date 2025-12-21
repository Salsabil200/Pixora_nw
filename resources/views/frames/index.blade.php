<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>PIXORA – Choose Your Frame</title>

<style>
body{
    margin:0;
    font-family:'Segoe UI',sans-serif;
    background:#fff7fb;
    color:#333;
}
header{
    background:linear-gradient(135deg,#fbc2eb,#a6c1ee);
    padding:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
header h1{
    margin:0;
    font-weight:900;
    color:#fff;
}
nav a, nav button{
    margin-left:18px;
    text-decoration:none;
    font-weight:700;
    color:#fff;
    background:none;
    border:none;
    cursor:pointer;
}
.container{
    padding:40px 8%;
}
.section-title{
    margin-top:70px;
    font-size:26px;
    font-weight:900;
}
.section-desc{
    margin-bottom:20px;
    color:#777;
}
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
    gap:24px;
}
.card{
    background:#fff;
    border-radius:18px;
    padding:14px;
    box-shadow:0 10px 24px rgba(0,0,0,.08);
    transition:.25s;
    text-align:center;
}
.card:hover{
    transform:translateY(-6px);
}
.thumb{
    height:300px;
    border-radius:14px;
    overflow:hidden;
    background:#f3f4f6;
}
.thumb img{
    width:100%;
    height:100%;
    object-fit:contain;
}
.card h4{
    margin:14px 0 6px;
    font-size:16px;
}
.card p{
    font-size:13px;
    color:#777;
}
.card a{
    display:inline-block;
    margin-top:12px;
    padding:9px 18px;
    background:#ff9ecf;
    color:#fff;
    text-decoration:none;
    border-radius:999px;
    font-size:13px;
    font-weight:800;
}
footer{
    margin-top:80px;
    padding:30px;
    text-align:center;
    color:#888;
}
</style>
</head>

<body>

<header>
    <h1>PIXORA</h1>
    <nav>
        <a href="/">Home</a>
        <a href="/gallery">Frames</a>

        @auth
            <form method="POST" action="/logout" style="display:inline;">
                @csrf
                <button>Logout</button>
            </form>
        @else
            <a href="/login">Login</a>
        @endauth
    </nav>
</header>

<div class="container">

{{-- ================= SEASON SERIES ================= --}}
<h2 class="section-title">Season Series</h2>
<p class="section-desc">Frame bertema musim dan perayaan.</p>

@php
$season = [
    ['name'=>'Winter','file'=>'assets/frames/season/Winter.png'],
    ['name'=>'Summer','file'=>'assets/frames/season/Summer.png'],
    ['name'=>'Tropical','file'=>'assets/frames/season/Tropical.png'],
    ['name'=>'Pumpkin','file'=>'assets/frames/season/Pumpkin.png'],
    ['name'=>'Night Christmas','file'=>'assets/frames/season/NightChristmas.png'],
];
@endphp

<div class="grid">
@foreach($season as $f)
<div class="card">
    <div class="thumb">
        <img src="{{ asset($f['file']) }}" alt="{{ $f['name'] }}">
    </div>
    <h4>{{ $f['name'] }}</h4>
    <p>Nuansa musim & suasana khas.</p>
    <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
</div>
@endforeach
</div>

{{-- ================= ART SERIES ================= --}}
<h2 class="section-title">Art Series</h2>
<p class="section-desc">Frame artistik dan clean aesthetic.</p>

@php
$art = [
    ['name'=>'Clean Art','file'=>'assets/frames/art/CleanArt.png'],
    ['name'=>'Blue Art','file'=>'assets/frames/art/BlueArt.png'],
    ['name'=>'Pinkish','file'=>'assets/frames/art/Pinkish.png'],
];
@endphp

<div class="grid">
@foreach($art as $f)
<div class="card">
    <div class="thumb">
        <img src="{{ asset($f['file']) }}">
    </div>
    <h4>{{ $f['name'] }}</h4>
    <p>Minimal & modern.</p>
    <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
</div>
@endforeach
</div>

{{-- ================= NATURE SERIES ================= --}}
<h2 class="section-title">Nature Series</h2>
<p class="section-desc">Terinspirasi dari alam & flora.</p>

@php
$nature = [
    ['name'=>'Leaf','file'=>'assets/frames/nature/Leaf.png'],
    ['name'=>'Floral','file'=>'assets/frames/nature/Floral.png'],
    ['name'=>'Flamingo','file'=>'assets/frames/nature/Flamingo.png'],
];
@endphp

<div class="grid">
@foreach($nature as $f)
<div class="card">
    <div class="thumb">
        <img src="{{ asset($f['file']) }}">
    </div>
    <h4>{{ $f['name'] }}</h4>
    <p>Fresh & natural vibes.</p>
    <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
</div>
@endforeach
</div>

{{-- ================= LIFESTYLE SERIES ================= --}}
<h2 class="section-title">Lifestyle Series</h2>
<p class="section-desc">Gaya hidup modern & ekspresif.</p>

@php
$lifestyle = [
    ['name'=>'Blue Daily','file'=>'assets/frames/lifestyle/BlueDaily.png'],
    ['name'=>'Glam','file'=>'assets/frames/lifestyle/Glam.png'],
    ['name'=>'Nightclub','file'=>'assets/frames/lifestyle/Nightclub.png'],
];
@endphp

<div class="grid">
@foreach($lifestyle as $f)
<div class="card">
    <div class="thumb">
        <img src="{{ asset($f['file']) }}">
    </div>
    <h4>{{ $f['name'] }}</h4>
    <p>Trendy & stylish.</p>
    <a href="/create?frame={{ $f['file'] }}">Pilih Frame</a>
</div>
@endforeach
</div>

</div>

<footer>
    <strong>PIXORA</strong><br>
    Capture your moments beautifully ✨
</footer>

</body>
</html>
