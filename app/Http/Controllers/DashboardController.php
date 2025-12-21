<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Dashboard untuk ADMIN
     */
    public function admin()
    {
        // 🔐 Cek login
        if (!Auth::check()) {
            return redirect('/login');
        }

        // 🚫 Bukan admin? tendang
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak');
        }

        return view('admin.dashboard');
    }

    /**
     * Dashboard untuk USER biasa
     */
    public function user()
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        return view('user.dashboard');
    }
}
