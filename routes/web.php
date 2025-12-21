<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PaymentController;

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/frames', [AdminController::class, 'frames']);
    Route::post('/frames/upload', [AdminController::class, 'uploadFrame']);
    Route::get('/frames/status', [AdminController::class, 'frameStatus']);
    Route::post('/frames/toggle/{id}', [AdminController::class, 'toggleFrame']);

    Route::get('/premium', [AdminController::class, 'premium']);
    Route::post('/premium/update', [AdminController::class, 'updatePremium']);

    Route::get('/transactions', [AdminController::class, 'transactions']);

    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('admin.dashboard');

    Route::get('/frames', [AdminController::class, 'frames'])
        ->name('admin.frames');

    Route::get('/premium', [AdminController::class, 'premium'])
        ->name('admin.premium');

    Route::get('/transactions', [AdminController::class, 'transactions'])
        ->name('admin.transactions');

    Route::get('/transactions/export', [AdminController::class, 'exportTransactions'])
    ->name('admin.transactions.export');

});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('home');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'registerForm']);
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout']);

/*
|--------------------------------------------------------------------------
| USER ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/frames', function () {
    $frames = \App\Models\Frame::where('is_active', true)->get();
    return view('frames.index', compact('frames'));
})->middleware('auth');


Route::get('/create', function () {
    $frame = request('frame');
    if (!$frame) abort(404);
    return view('create', compact('frame'));
})->middleware('auth');

/*
|--------------------------------------------------------------------------
| PAYMENT
|--------------------------------------------------------------------------
*/
Route::post('/pay/premium', [PaymentController::class, 'premium'])
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| DEBUG
|--------------------------------------------------------------------------
*/
Route::get('/test-laravel', function () {
    return 'LARAVEL HIDUP';
});
