<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>PIXORA Admin Dashboard</title>

    <style>
        body{
            margin:0;
            font-family: 'Inter', sans-serif;
            background:#f4f6fb;
        }

        /* HEADER */
        .admin-header{
            background:linear-gradient(90deg,#000,#222);
            color:white;
            padding:18px 32px;
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .admin-header h1{
            font-size:20px;
            margin:0;
            font-weight:800;
        }

        .admin-header a{
            color:white;
            text-decoration:none;
            font-weight:600;
            opacity:.9;
        }

        /* CONTAINER */
        .container{
            max-width:1100px;
            margin:40px auto;
            padding:0 24px;
        }

        h2{
            margin-bottom:20px;
            font-size:24px;
        }

        /* GRID */
        .admin-grid{
            display:grid;
            grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
            gap:22px;
        }

        /* CARD */
        .admin-card{
            background:white;
            border-radius:18px;
            padding:28px 24px;
            box-shadow:0 12px 30px rgba(0,0,0,.08);
            text-decoration:none;
            color:#111;
            display:flex;
            gap:16px;
            align-items:center;
            transition:.25s ease;
        }

        .admin-card:hover{
            transform:translateY(-4px);
            box-shadow:0 18px 40px rgba(0,0,0,.12);
        }

        .icon{
            font-size:28px;
            width:52px;
            height:52px;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:14px;
            color:white;
        }

        .bg-pink{ background:#ec4899; }
        .bg-green{ background:#22c55e; }
        .bg-blue{ background:#3b82f6; }
        .bg-yellow{ background:#f59e0b; }

        .card-text h3{
            margin:0;
            font-size:18px;
            font-weight:800;
        }

        .card-text p{
            margin:6px 0 0;
            font-size:13px;
            color:#666;
        }
    </style>
</head>
<body>

<!-- HEADER -->
<div class="admin-header">
    <h1>PIXORA Admin</h1>
    <a href="/logout"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Logout
    </a>

    <form id="logout-form" action="/logout" method="POST" style="display:none;">
        @csrf
    </form>
</div>

<!-- CONTENT -->
<div class="container">
    <h2>Dashboard Admin</h2>

    <div class="admin-grid">

        <a href="{{ route('admin.frames') }}" class="admin-card">
            <div class="icon bg-pink">📸</div>
            <div class="card-text">
                <h3>Upload Frame Baru</h3>
                <p>Tambah frame photobox baru</p>
            </div>
        </a>

        <a href="{{ route('admin.frames') }}" class="admin-card">
            <div class="icon bg-green">🟢</div>
            <div class="card-text">
                <h3>Aktif / Nonaktif Frame</h3>
                <p>Kontrol ketersediaan frame</p>
            </div>
        </a>

        <a href="{{ route('admin.premium') }}" class="admin-card">
            <div class="icon bg-blue">💎</div>
            <div class="card-text">
                <h3>Atur Harga Premium</h3>
                <p>Kelola harga fitur premium</p>
            </div>
        </a>

        <a href="{{ route('admin.transactions') }}" class="admin-card">
            <div class="icon bg-yellow">💳</div>
            <div class="card-text">
                <h3>Transaksi Midtrans</h3>
                <p>Lihat pembayaran user</p>
            </div>
        </a>

    </div>
</div>

</body>
</html>
