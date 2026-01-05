<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| PUBLIC & AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () { return view('welcome'); });

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Google Auth
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->user();
        $user = User::updateOrCreate(
            ['email' => $googleUser->email],
            [
                'name' => $googleUser->name,
                'google_id' => $googleUser->id,
                'password' => bcrypt(str()->random(16)),
            ]
        );
        Auth::login($user);
        return auth()->user()->role === 'admin' ? redirect()->route('admin.dashboard') : redirect('/home');
    } catch (\Exception $e) {
        return redirect('/login')->with('error', 'Gagal login pakai Google');
    }
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/home', function () { return view('home'); })->name('home');

    Route::get('/frames', function () {
        $frames = \App\Models\Frame::where('is_active', true)->get();
        return view('frames.index', compact('frames'));
    })->name('frames.index');

    Route::get('/create', function () {
        $frame = request('frame');
        if (!$frame) abort(404);
        return view('create', compact('frame'));
    })->name('frames.create');

    // Payment Routes
    Route::post('/payment/tokenize', [PaymentController::class, 'tokenize'])->name('payment.tokenize');
    Route::post('/payment/update-local', [PaymentController::class, 'updateLocalStatus'])->name('payment.update-local');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Frames
    Route::get('/frames', [AdminController::class, 'frames'])->name('frames');
    Route::post('/frames/upload', [AdminController::class, 'uploadFrame'])->name('frames.upload');
    Route::post('/frames/toggle/{id}', [AdminController::class, 'toggleFrame'])->name('frames.toggle');

    // --- PREMIUM ROUTES (DITAMBAHKAN BIAR GAK ERROR) ---
    Route::get('/premium', [AdminController::class, 'premium'])->name('premium');
    Route::post('/premium/update', [AdminController::class, 'updatePremium'])->name('premium.update');

    // Transactions
    Route::get('/transactions', [AdminController::class, 'transactions'])->name('transactions');
    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])->name('transactions.export');
    Route::get('/transactions/{order_id}/sync', [AdminController::class, 'syncWithMidtrans'])->name('transactions.sync');
});

Route::get('/test-laravel', function () { return 'LARAVEL HIDUP'; });