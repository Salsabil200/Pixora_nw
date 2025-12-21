<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Frame</title>

    <style>
        body{
            font-family:Inter, sans-serif;
            background:#f4f6fb;
            margin:0;
        }

        .header{
            background:#000;
            color:white;
            padding:18px 30px;
            font-weight:800;
        }

        .container{
            max-width:1100px;
            margin:30px auto;
            padding:0 24px;
        }

        h2{
            margin-bottom:16px;
        }

        .card{
            background:white;
            border-radius:16px;
            padding:20px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            margin-bottom:24px;
        }

        input, button{
            padding:12px;
            border-radius:10px;
            border:1px solid #ddd;
        }

        button{
            background:#ec4899;
            color:white;
            font-weight:700;
            cursor:pointer;
            border:none;
        }

        .grid{
            display:grid;
            grid-template-columns:repeat(auto-fill,minmax(220px,1fr));
            gap:20px;
        }

        .frame-item{
            background:#fff;
            border-radius:14px;
            padding:14px;
            box-shadow:0 6px 20px rgba(0,0,0,.1);
        }

        .frame-item img{
            width:100%;
            border-radius:10px;
            margin-bottom:10px;
        }

        .status{
            font-size:13px;
            margin-bottom:10px;
        }

        .active{ color:green; }
        .inactive{ color:red; }
    </style>
</head>
<body>

<div class="header">PIXORA Admin – Kelola Frame</div>

<div class="container">

    @if(session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <!-- UPLOAD -->
    <div class="card">
        <h2>Upload Frame Baru</h2>

        <form method="POST" action="/admin/frames/upload" enctype="multipart/form-data">
            @csrf
            <input type="text" name="name" placeholder="Nama Frame" required>
            <input type="file" name="image" required>
            <button>Upload</button>
        </form>
    </div>

    <!-- LIST FRAME -->
    <div class="card">
        <h2>Daftar Frame</h2>

        <div class="grid">
            @foreach($frames as $frame)
                <div class="frame-item">
                    <img src="{{ asset('storage/'.$frame->image) }}">
                    <strong>{{ $frame->name }}</strong>

                    <div class="status {{ $frame->is_active ? 'active' : 'inactive' }}">
                        {{ $frame->is_active ? 'Aktif' : 'Nonaktif' }}
                    </div>

                    <form method="POST" action="/admin/frames/toggle/{{ $frame->id }}">
                        @csrf
                        <button type="submit">
                            {{ $frame->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

</div>

</body>
</html>
