<!DOCTYPE html>
<html>
<head>
    <title>Aktif / Nonaktif Frame</title>
    <style>
        body{font-family:Inter;background:#f4f6fb}
        .container{max-width:1000px;margin:40px auto}
        .card{
            background:#fff;
            padding:18px;
            border-radius:16px;
            box-shadow:0 10px 30px rgba(0,0,0,.1);
            margin-bottom:16px;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }
        img{width:120px;border-radius:12px}
        button{
            padding:10px 18px;
            border-radius:999px;
            border:none;
            font-weight:700;
            cursor:pointer;
        }
        .on{background:#22c55e;color:white}
        .off{background:#ef4444;color:white}
    </style>
</head>
<body>

<div class="container">
    <h2>Aktif / Nonaktif Frame</h2>
    <p>Kontrol ketersediaan frame untuk user</p>

    @foreach($frames as $frame)
        <div class="card">
            <div style="display:flex;gap:16px;align-items:center">
                <img src="{{ asset('storage/'.$frame->image) }}">
                <div>
                    <strong>{{ $frame->name }}</strong><br>
                    Status:
                    <b>{{ $frame->is_active ? 'Aktif' : 'Nonaktif' }}</b>
                </div>
            </div>

            <form method="POST" action="/admin/frames/toggle/{{ $frame->id }}">
                @csrf
                <button class="{{ $frame->is_active ? 'off' : 'on' }}">
                    {{ $frame->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
            </form>
        </div>
    @endforeach
</div>

</body>
</html>
