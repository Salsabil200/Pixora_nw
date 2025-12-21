<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengaturan Premium - PIXORA Admin</title>

    <style>
        body{
            margin:0;
            font-family: 'Inter', system-ui, sans-serif;
            background: #f4f6fb;
            color:#111;
        }

        /* ===== HEADER ===== */
        .topbar{
            background:#0f0f10;
            color:white;
            padding:18px 32px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }
        .topbar strong{
            font-size:20px;
        }
        .topbar a{
            color:white;
            text-decoration:none;
            font-weight:600;
        }

        /* ===== CONTAINER ===== */
        .container{
            max-width:720px;
            margin:60px auto;
            padding:0 20px;
        }

        h1{
            font-size:28px;
            margin-bottom:8px;
        }
        p.subtitle{
            color:#6b7280;
            margin-bottom:28px;
        }

        /* ===== CARD ===== */
        .card{
            background:white;
            border-radius:22px;
            padding:32px;
            box-shadow:0 25px 60px rgba(0,0,0,.08);
        }

        .field{
            margin-bottom:26px;
        }

        label{
            font-weight:700;
            display:block;
            margin-bottom:10px;
        }

        .input-group{
            display:flex;
            align-items:center;
            border:2px solid #e5e7eb;
            border-radius:14px;
            padding:14px 16px;
            transition:.2s;
        }
        .input-group:focus-within{
            border-color:#ec4899;
        }

        .prefix{
            font-weight:700;
            margin-right:10px;
            color:#6b7280;
        }

        input{
            border:none;
            outline:none;
            font-size:18px;
            font-weight:700;
            width:100%;
        }

        /* ===== BUTTON ===== */
        button{
            width:100%;
            padding:16px;
            border:none;
            border-radius:999px;
            font-size:16px;
            font-weight:800;
            cursor:pointer;
            background:linear-gradient(135deg,#ec4899,#a855f7);
            color:white;
            transition:.2s;
        }
        button:hover{
            opacity:.9;
            transform:translateY(-1px);
        }

        /* ===== INFO BOX ===== */
        .info{
            margin-top:20px;
            padding:16px;
            background:#fdf2f8;
            border-radius:14px;
            font-size:14px;
            color:#9d174d;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="topbar">
    <strong>PIXORA Admin</strong>
    <a href="/admin/dashboard">Dashboard</a>
</div>

<!-- CONTENT -->
<div class="container">
    <h1>Pengaturan Premium</h1>
    <p class="subtitle">
        Atur harga fitur premium yang akan dikenakan kepada user PIXORA.
    </p>

    <div class="card">
        <form method="POST" action="/admin/premium/update">
            @csrf

            <div class="field">
                <label>Harga Premium</label>
                <div class="input-group">
                    <span class="prefix">Rp</span>
                    <input
                        type="number"
                        name="price"
                        value="9000"
                        min="0"
                    >
                </div>
            </div>

            <button type="submit">
                💎 Simpan Perubahan
            </button>
        </form>

        <div class="info">
            💡 Harga ini akan digunakan untuk semua fitur premium (download video, dll).
        </div>
    </div>
</div>

</body>
</html>
