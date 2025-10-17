<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('dokumen_magang', function (Blueprint $table) {
            // Hapus foreign key lama kalau sudah ada
            $table->dropForeign(['magang_id']);

            // Tambahkan foreign key baru dengan cascade delete
            $table->foreign('magang_id')
                ->references('magang_id')
                ->on('magang')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen_magang', function (Blueprint $table) {
            // Kembalikan ke default (tanpa cascade)
            $table->dropForeign(['magang_id']);

            $table->foreign('magang_id')
                ->references('magang_id')
                ->on('magang')
                ->onDelete('restrict'); // default MySQL behavior
        });
    }
};
