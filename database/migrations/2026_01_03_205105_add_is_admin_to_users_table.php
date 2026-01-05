<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    // Cek dulu di tabel users, kalau kolom 'role' belum ada, baru tambahkan
    if (!Schema::hasColumn('users', 'role')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('user')->after('password');
        });
    }
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        if (Schema::hasColumn('users', 'role')) {
            $table->dropColumn('role');
        }
    });
}
};