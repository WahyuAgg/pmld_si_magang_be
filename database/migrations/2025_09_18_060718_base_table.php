<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ======================
        // Table: users
        // ======================
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('role', ['admin', 'mahasiswa', 'mitra', 'dosbing']);
            $table->string('email')->unique()->nullable();
            $table->timestamps();
        });

        // ======================
        // Table: dosen_pembimbing
        // ======================
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('dosbing_id');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->timestamps();
        });

        // ======================
        // Table: mitra
        // ======================
        Schema::create('mitra', function (Blueprint $table) {
            $table->id('mitra_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_mitra', 150);
            $table->string('email', 100)->nullable();
            $table->string('narahubung', 100)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');

        });





        // ======================
        // Table: mahasiswa
        // ======================
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->id('mahasiswa_id');
            $table->unsignedBigInteger('user_id');
            $table->string('nim', 20)->unique();
            $table->string('nama', 100);
            $table->string('angkatan', 10)->nullable();
            $table->timestamps();



            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });


        // ======================
        // Table: magang
        // ======================
        Schema::create('magang', function (Blueprint $table) {
            $table->id('magang_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mitra_id')->nullable();
            $table->unsignedBigInteger('dosbing_id')->nullable();
            $table->integer('semester_magang');
            $table->string('role_magang', 100)->nullable();
            $table->text('jobdesk')->nullable();
            $table->integer('periode_bulan')->default(5);
            $table->timestamps();

            // Relasi ke tabel mahasiswa
            $table->foreign('mahasiswa_id')
                ->references('mahasiswa_id')
                ->on('mahasiswa')
                ->onDelete('cascade');

            // Relasi ke tabel mitra → jika mitra dihapus, set null
            $table->foreign('mitra_id')
                ->references('mitra_id')
                ->on('mitra')
                ->nullOnDelete(); // ini yang kamu butuhkan

            // Relasi ke dosen pembimbing → set null kalau dosbing dihapus
            $table->foreign('dosbing_id')
                ->references('dosbing_id')
                ->on('dosen_pembimbing')
                ->nullOnDelete();
        });


    }

    public function down(): void
    {
        Schema::dropIfExists('magang');
        Schema::dropIfExists('supervisor');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('data_admin');
        Schema::dropIfExists('users');
        Schema::dropIfExists('mitra');
        Schema::dropIfExists('dosen_pembimbing');
    }
};

