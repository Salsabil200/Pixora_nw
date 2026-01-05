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
        Schema::table('users', function (Blueprint $table) {
            // Kita tambahkan kolom google_id setelah kolom email
            // nullable() supaya user lama yang daftar manual nggak error
            $table->string('google_id')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Untuk jaga-jaga kalau migrasi di-rollback, kolom dihapus lagi
            $table->dropColumn('google_id');
        });
    }
};