<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions · PIXORA Admin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600,800" rel="stylesheet" />
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
            padding: 24px; 
            box-sizing: border-box; 
            display: flex;
            flex-direction: column;
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
            display: flex; 
            align-items: center; 
            gap: 12px; 
            transition: 0.3s; 
        }

        .nav-link:hover, .nav-link.active { 
            background: #fdf2f8; 
            color: var(--primary); 
        }

        /* LOGOUT BUTTON */
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

        .btn-logout:hover { 
            background: #fef2f2; 
        }

        /* CONTENT */
        .main-content { 
            margin-left: var(--sidebar-width); 
            width: calc(100% - var(--sidebar-width)); 
            padding: 40px; 
            box-sizing: border-box;
        }

        header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 32px; 
        }

        .btn-export { 
            background: #0f172a; 
            color: white; 
            padding: 12px 20px; 
            border-radius: 12px; 
            text-decoration: none; 
            font-weight: 700; 
            font-size: 14px; 
            transition: 0.2s; 
        }
        
        .btn-export:hover {
            background: #1e293b;
            transform: translateY(-1px);
        }

        /* TABLE */
        .table-container { 
            background: white; 
            border-radius: 16px; 
            border: 1px solid #e2e8f0; 
            overflow: hidden; 
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); 
        }

        table { width: 100%; border-collapse: collapse; }
        th { background: #f8fafc; padding: 16px 24px; font-size: 12px; text-transform: uppercase; color: #64748b; text-align: left; border-bottom: 1px solid #e2e8f0; }
        td { padding: 16px 24px; border-bottom: 1px solid #f1f5f9; font-size: 14px; }
        
        /* BADGES */
        .badge { padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700; text-transform: capitalize; }
        .badge-settlement { background: #dcfce7; color: #16a34a; }
        .badge-pending { background: #fef9c3; color: #a16207; }
        .badge-expire { background: #fee2e2; color: #dc2626; }

        .user-meta { display: flex; align-items: center; gap: 10px; }
        .avatar { width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: bold; }
    </style>
</head>
<body>

<aside class="sidebar">
    <div class="logo-area">PIXORA ✨</div>
    <nav class="nav-menu">
        <a href="{{ route('admin.dashboard') }}" class="nav-link">🏠 Dashboard</a>
        <a href="{{ route('admin.transactions') }}" class="nav-link active">💰 Transactions</a>
        <a href="{{ route('admin.premium') }}" class="nav-link">💎 Pricing Strategy</a>
    </nav>

    <a href="/logout" class="btn-logout" 
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        🚪 Logout
    </a>
    <form id="logout-form" action="/logout" method="POST" style="display:none;">@csrf</form>
</aside>

<main class="main-content">
    <header>
        <div>
            <h1 style="margin:0">Riwayat Transaksi</h1>
            <p style="color:#64748b; margin:4px 0 0">Pantau semua pendapatan masuk secara real-time.</p>
        </div>
        <a href="{{ route('admin.transactions.export') }}" class="btn-export">📥 Export CSV</a>
    </header>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Order ID</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $t)
                <tr>
                    <td>
                        <div class="user-meta">
                            <div class="avatar">{{ substr($t->user->name ?? 'U', 0, 1) }}</div>
                            <div>
                                <strong>{{ $t->user->name ?? 'Guest' }}</strong><br>
                                <small style="color: #94a3b8">{{ $t->user->email ?? '-' }}</small>
                            </div>
                        </div>
                    </td>
                    <td style="font-family: monospace; font-weight: 600;">{{ $t->order_id }}</td>
                    <td style="font-weight: 800;">Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                    <td style="color: #64748b">{{ $t->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <span class="badge badge-{{ $t->status }}">
                            {{ $t->status }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.transactions.sync', $t->order_id) }}" style="text-decoration:none; font-size:12px; color:var(--primary); font-weight:bold">🔄 Sync</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 48px; color: #94a3b8;">Belum ada transaksi hari ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $transactions->links() }}
    </div>
</main>

</body>
</html>