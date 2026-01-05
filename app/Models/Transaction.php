<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Ini hukumnya WAJIB agar kolom di database bisa diisi lewat Controller
    protected $fillable = [
        'user_id',
        'order_id',
        'amount',
        'status',
        'snap_token',
        // 'is_subscription', // Tambahkan ini JIKA di database ada kolomnya
    ];

    /**
     * Relasi ke User (opsional, tapi bagus buat admin panel nanti)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}