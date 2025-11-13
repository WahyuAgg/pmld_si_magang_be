<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('jadwal_presentasi', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['magang_id']);

            // Hapus kolom magang_id
            $table->dropColumn('magang_id');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('jadwal_presentasi', function (Blueprint $table) {
            // Tambahkan kembali kolom magang_id
            $table->unsignedBigInteger('magang_id')->nullable();

            // Tambahkan kembali foreign key-nya
            $table->foreign('magang_id')->references('magang_id')->on('magang');
        });
    }
};
