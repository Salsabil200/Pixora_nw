<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Settings · PIXORA Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800" rel="stylesheet" />

    <style>
        :root {
            --primary: #ec4899;
            --secondary: #a855f7;
            --bg-body: #f8fafc;
            /* DISERAGAMKAN MENJADI 240PX */
            --sidebar-width: 240px;
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background: var(--bg-body);
            color: #1e293b;
            display: flex;
        }

        /* SIDEBAR (Konsisten dengan halaman lain) */
        .sidebar {
            width: var(--sidebar-width);
            background: #ffffff;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 24px;
            box-sizing: border-box;
            z-index: 50;
        }

        .logo-area { 
            font-size: 22px; 
            font-weight: 800; 
            color: var(--primary); 
            margin-bottom: 40px; 
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .nav-menu { display: flex; flex-direction: column; gap: 8px; flex: 1; }
        
        .nav-link {
            text-decoration: none;
            color: #64748b;
            padding: 12px 16px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
            display: flex;
            align-items: center; 
            gap: 12px;
        }
        .nav-link:hover, .nav-link.active { background: #fdf2f8; color: var(--primary); }

        /* TOMBOL LOGOUT */
        .btn-logout {
            margin-top: auto;
            color: #ef4444;
            padding: 12px 16px;
            text-decoration: none;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            border-radius: 12px;
            transition: 0.3s;
        }
        .btn-logout:hover { background: #fef2f2; }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 40px;
            display: flex;
            justify-content: center;
            box-sizing: border-box;
        }

        .content-wrapper {
            max-width: 600px;
            width: 100%;
        }

        header h1 { margin: 0; font-size: 32px; font-weight: 800; letter-spacing: -1px; }
        header p { color: #64748b; margin-top: 8px; margin-bottom: 32px; }

        /* CARD STYLE */
        .card {
            background: white;
            border-radius: 24px;
            padding: 40px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        }

        label {
            font-weight: 700;
            display: block;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 16px 20px;
            transition: 0.3s;
        }

        .input-wrapper:focus-within {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.1);
        }

        .prefix {
            font-weight: 800;
            color: #94a3b8;
            margin-right: 12px;
            font-size: 18px;
        }

        input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 24px;
            font-weight: 800;
            width: 100%;
            color: #0f172a;
            font-family: inherit;
        }

        /* BUTTON GRADIENT */
        button {
            width: 100%;
            margin-top: 24px;
            padding: 18px;
            border: none;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: 0 10px 15px -3px rgba(236, 72, 153, 0.3);
            transition: 0.3s;
            font-family: inherit;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(236, 72, 153, 0.4);
        }

        /* TIP BOX */
        .tip-box {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            background: #eff6ff;
            padding: 20px;
            border-radius: 16px;
            border: 1px solid #dbeafe;
        }

        .tip-icon { font-size: 24px; }
        .tip-text { font-size: 14px; color: #1e40af; line-height: 1.5; }
        .tip-text strong { display: block; margin-bottom: 4px; }

    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo-area">PIXORA ✨</div>
    <nav class="nav-menu">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">🏠 Dashboard</a>
        <a href="{{ route('admin.transactions') }}" class="nav-link">💰 Transactions</a>
        <a href="{{ route('admin.premium') }}" class="nav-link active">💎 Pricing Strategy</a>
    </nav>

    <a href="/logout" class="btn-logout" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        🚪 Logout
    </a>
    <form id="logout-form" action="/logout" method="POST" style="display:none;">@csrf</form>
</aside>

<main class="main-content">
    <div class="content-wrapper">
        <header>
            <h1>Premium Pricing</h1>
            <p>Tentukan strategi harga fitur eksklusif untuk meningkatkan pendapatan PIXORA.</p>
        </header>

        <div class="card">
            <form method="POST" action="/admin/premium/update">
                @csrf
                <div class="field">
                    <label>Harga Per Transaksi / Fitur</label>
                    <div class="input-wrapper">
                        <span class="prefix">Rp</span>
                        <input type="number" name="price" value="9000" min="0">
                    </div>
                </div>

                <button type="submit">
                    Simpan Perubahan Global
                </button>
            </form>

            <div class="tip-box">
                <div class="tip-icon">💡</div>
                <div class="tip-text">
                    <strong>Informasi Strategi:</strong>
                    Harga ini akan langsung sinkron dengan gateway pembayaran Midtrans. Pastikan harga sudah termasuk biaya admin bank.
                </div>
            </div>
        </div>
    </div>
</main>

</body>
</html>