<!DOCTYPE html>
<html>
<head>
<title>Admin – Transactions</title>

<style>
body{font-family:Inter;background:#f9fafb;}
.wrapper{max-width:900px;margin:auto;padding:40px;}
table{
    width:100%;
    border-collapse:collapse;
    background:white;
    border-radius:18px;
    overflow:hidden;
}
th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
}
th{
    background:#fdf2f8;
}
</style>
</head>

<body>
<div class="wrapper">

<h2>Riwayat Transaksi</h2>
<div style="display:flex; justify-content:flex-end; margin-bottom:20px;">
    <a href="{{ route('admin.transactions.export') }}"
       style="
            background:#111;
            color:white;
            padding:12px 22px;
            border-radius:999px;
            text-decoration:none;
            font-weight:700;
       ">
        ⬇️ Export CSV
    </a>
</div>

<table>
<tr>
<th>User</th>
<th>Tanggal</th>
<th>Jumlah</th>
<th>Status</th>
</tr>

<tr>
<td>User A</td>
<td>2025-01-01</td>
<td>Rp 9.000</td>
<td>Success</td>
</tr>

</table>

</div>
</body>
</html>
