<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            // Ubah kolom menjadi nullable
            $table->unsignedBigInteger('supervisor_id')->nullable()->change();
            // $table->unsignedBigInteger('dosbing_id')->nullable()->change();
            // $table->year('tahun_ajaran')->nullable()->change();
            // $table->integer('semester_magang')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            // Kembalikan jadi wajib diisi
            $table->unsignedBigInteger('supervisor_id')->nullable(false)->change();
            // $table->unsignedBigInteger('dosbing_id')->nullable(false)->change();
            // $table->year('tahun_ajaran')->nullable(false)->change();
            // $table->integer('semester_magang')->nullable(false)->change();
        });
    }
};
