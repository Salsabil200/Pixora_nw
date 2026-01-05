<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // Izinkan kolom key dan value diisi secara massal
    protected $fillable = ['key', 'value'];
}