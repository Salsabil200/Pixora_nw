<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function frames()
    {
        $frames = Frame::latest()->get();
        return view('admin.frames', compact('frames'));
    }

    public function uploadFrame(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'image' => 'required|image'
        ]);

        $path = $request->file('image')->store('frames', 'public');

        Frame::create([
            'name' => $request->name,
            'image' => $path,
            'is_active' => true
        ]);

        return back()->with('success', 'Frame berhasil diupload');
    }

    // 🔥 INI FITUR AKTIF / NONAKTIF
    public function frameStatus()
    {
        $frames = Frame::orderBy('created_at','desc')->get();
        return view('admin.frames-status', compact('frames'));
    }

    public function toggleFrame($id)
    {
        $frame = Frame::findOrFail($id);
        $frame->is_active = !$frame->is_active;
        $frame->save();

        return back();
    }

    public function premium()
    {
        return view('admin.premium');
    }

    public function transactions()
    {
        return view('admin.transactions');
    }
    public function exportTransactions()
{
    $transactions = [
        [
            'user' => 'User A',
            'date' => '2025-01-01',
            'amount' => 9000,
            'status' => 'Success'
        ],
        // nanti bisa ganti dari DB
    ];

    $headers = [
        "Content-Type" => "text/csv",
        "Content-Disposition" => "attachment; filename=transactions.csv",
    ];

    $callback = function() use ($transactions) {
        $file = fopen('php://output', 'w');

        // Header CSV
        fputcsv($file, ['User', 'Tanggal', 'Jumlah', 'Status']);

        foreach ($transactions as $t) {
            fputcsv($file, [
                $t['user'],
                $t['date'],
                'Rp ' . number_format($t['amount'], 0, ',', '.'),
                $t['status']
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
}
