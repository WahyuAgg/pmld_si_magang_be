<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::table('foto_kegiatan', function (Blueprint $table) {
    $table->timestamp('created_at')->nullable();
    $table->timestamp('updated_at')->nullable();
});
    }

    public function down(): void
    {
        Schema::table('foto_kegiatan', function (Blueprint $table) {
            $table->dropTimestamps();
        });
    }
};