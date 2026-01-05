<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PIXORA · Admin Panel</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #ec4899;
            --primary-dark: #db2777;
            --bg-body: #f8fafc;
            --sidebar-width: 240px; 
        }

        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background: var(--bg-body);
            color: #1e293b;
            display: flex;
        }

        /* SIDEBAR */
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

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
        }

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

        .nav-link:hover, .nav-link.active {
            background: #fdf2f8;
            color: var(--primary);
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            padding: 40px;
            box-sizing: border-box;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
        }

        .welcome-text h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
        }

        /* USER LIST TABLE */
        .user-card {
            background: white;
            padding: 24px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            margin-bottom: 32px;
        }

        .user-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .user-table th {
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #f1f5f9;
            color: #64748b;
            font-size: 14px;
        }

        .user-table td {
            padding: 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        .badge-user {
            background: #f1f5f9;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }

        /* CHART SECTION */
        .chart-card {
            background: white;
            padding: 24px;
            border-radius: 24px;
            border: 1px solid #f1f5f9;
            margin-bottom: 40px;
        }

        .chart-container {
            position: relative;
            height: 250px;
            width: 100%;
        }

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
        }

        .btn-logout:hover { background: #fef2f2; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo-area">PIXORA ✨</div>
    <nav class="nav-menu">
        <a href="#" class="nav-link active">🏠 Dashboard</a>
        <a href="{{ route('admin.transactions') }}" class="nav-link">💰 Transactions</a>
        <a href="{{ route('admin.premium') }}" class="nav-link">💎 Pricing Strategy</a>
    </nav>

    <a href="/logout" class="btn-logout" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        🚪 Logout
    </a>
</aside>

<main class="main-content">
    <header>
        <div class="welcome-text">
            <h1>Halo, {{ auth()->user()->name }} 👋</h1>
            <p>Berikut daftar pengguna yang terdaftar di PIXORA.</p>
        </div>
        <form id="logout-form" action="/logout" method="POST" style="display:none;">@csrf</form>
    </header>

    <div class="user-card">
        <h3 style="margin-top: 0;">👥 Daftar Pengguna Terdaftar</h3>
        <table class="user-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Tgl Bergabung</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->take(10)->get() as $user)
                <tr>
                    <td><strong>{{ $user->name }}</strong></td>
                    <td>{{ $user->email }}</td>
                    <td><span class="badge-user">{{ strtoupper($user->role) }}</span></td>
                    <td>{{ $user->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <p style="font-size: 12px; color: #94a3b8; margin-top: 15px;">*Menampilkan 10 user terbaru</p>
    </div>

    <div class="chart-card">
        <h3>📈 Grafik Pengunjung</h3>
        <div class="chart-container">
            <canvas id="visitorChart"></canvas>
        </div>
    </div>
</main>

<script>
    const ctx = document.getElementById('visitorChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 250);
    gradient.addColorStop(0, 'rgba(236, 72, 153, 0.2)');
    gradient.addColorStop(1, 'rgba(236, 72, 153, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            datasets: [{
                label: 'Pengunjung',
                data: [45, 82, 60, 110, 95, 140, 165], 
                borderColor: '#ec4899',
                borderWidth: 3,
                tension: 0.4, 
                fill: true,
                backgroundColor: gradient,
                pointBackgroundColor: '#ec4899',
                pointBorderColor: '#fff',
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } }
        }
    });
</script>

</body>
</html>