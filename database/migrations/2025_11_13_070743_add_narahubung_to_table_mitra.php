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
        Schema::table('mitra', function (Blueprint $table) {
            $table->string('narahubung', 100)->nullable()->after('bidang_usaha');
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        Schema::table('mitra', function (Blueprint $table) {
            $table->dropColumn('narahubung');
        });
    }
};
