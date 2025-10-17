<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            $table->dropForeign(['mitra_id']);
            $table->foreign('mitra_id')
                ->references('mitra_id')
                ->on('mitra')
                ->onDelete('cascade');
            $table->foreign('supervisor_id')->references('supervisor_id')->on('supervisor')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('magang', function (Blueprint $table) {
            $table->dropForeign(['mitra_id']);
            $table->foreign('mitra_id')
                ->references('mitra_id')
                ->on('mitra')
                ->onDelete('restrict');
            $table->foreign('supervisor_id')->references('supervisor_id')->on('supervisor')->onDelete('restrict');

        });
    }
};
