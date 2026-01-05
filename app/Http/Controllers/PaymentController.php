<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Transaction;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function tokenize(Request $request)
    {
        // 1. Ambil semua konfigurasi dari file config/midtrans.php
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production'); // SEKARANG BACA DARI CONFIG
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        if (!Config::$serverKey) {
            return response()->json(['error' => 'Server Key Midtrans belum diatur di .env'], 500);
        }

        // --- LOGIC HARGA DINAMIS ---
        $priceSetting = Setting::where('key', 'premium_price')->first();
        $amount = $priceSetting ? (int)$priceSetting->value : 10000;

        $orderId = 'PIX-' . time();
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            Transaction::create([
                'user_id' => Auth::id(),
                'order_id' => $orderId,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            return response()->json(['token' => $snapToken, 'order_id' => $orderId]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Midtrans Error: ' . $e->getMessage()], 500);
        }
    }

    public function updateLocalStatus(Request $request)
    {
        $transaction = Transaction::where('order_id', $request->order_id)->first();
        if ($transaction) {
            $transaction->update(['status' => $request->status]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }
}