<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frame;
use App\Models\Transaction; 
use App\Models\Setting; // TAMBAHKAN INI BANG
use Illuminate\Support\Facades\Storage;
use Midtrans\Config;
use Midtrans\Transaction as MidtransTransaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_frames' => Frame::count(),
            'active_frames' => Frame::where('is_active', true)->count(),
            'total_revenue' => Transaction::where('status', 'settlement')->sum('amount'),
            'pending_transactions' => Transaction::where('status', 'pending')->count(),
        ];
        
        return view('admin.dashboard', compact('stats'));
    }

    public function frames()
    {
        $frames = Frame::latest()->paginate(10);
        return view('admin.frames', compact('frames'));
    }

    public function uploadFrame(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('frames', 'public');

            Frame::create([
                'name' => $request->name,
                'image' => $path,
                'is_active' => true
            ]);

            return back()->with('success', 'Frame berhasil diupload! ✨');
        }

        return back()->with('error', 'Gagal upload file.');
    }

    public function toggleFrame($id)
    {
        $frame = Frame::findOrFail($id);
        $frame->is_active = !$frame->is_active;
        $frame->save();

        $status = $frame->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Frame {$frame->name} berhasil {$status}.");
    }

    // --- FITUR PREMIUM (SUDAH TERHUBUNG DATABASE) ---
    public function premium()
    {
        // Ambil harga dari DB agar form menampilkan harga terbaru
        $price = Setting::where('key', 'premium_price')->value('value') ?? 50000;
        return view('admin.premium', compact('price'));
    }

    public function updatePremium(Request $request)
    {
        $request->validate([
            'price' => 'required|numeric|min:500' 
        ]);

        // Simpan harga baru ke tabel settings
        Setting::updateOrCreate(
            ['key' => 'premium_price'],
            ['value' => $request->price]
        );

        return back()->with('success', 'Harga premium berhasil diperbarui menjadi Rp' . number_format($request->price));
    }

    // --- FITUR TRANSAKSI ---
    public function transactions()
    {
        $transactions = Transaction::with('user')->latest()->paginate(15);
        return view('admin.transactions', compact('transactions'));
    }

    public function syncWithMidtrans($order_id)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $status = MidtransTransaction::status($order_id);
            $transaction = Transaction::where('order_id', $order_id)->firstOrFail();
            
            $transaction->update(['status' => $status->transaction_status]);

            return back()->with('success', "Status pesanan {$order_id} sekarang: {$status->transaction_status}");
        } catch (\Exception $e) {
            return back()->with('error', "Gagal sinkron: " . $e->getMessage());
        }
    }

    public function exportTransactions()
    {
        $transactions = Transaction::with('user')->get();
        $fileName = 'pixora-report-' . date('Y-m-d') . '.csv';
        
        return response()->stream(function() use ($transactions) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); 
            
            fputcsv($file, ['Order ID', 'Customer', 'Email', 'Amount', 'Status', 'Date']);

            foreach ($transactions as $t) {
                fputcsv($file, [
                    $t->order_id,
                    $t->user->name ?? 'Deleted User',
                    $t->user->email ?? '-',
                    $t->amount,
                    $t->status,
                    $t->created_at->format('d/m/Y H:i')
                ]);
            }
            fclose($file);
        }, 200, [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ]);
    }
}