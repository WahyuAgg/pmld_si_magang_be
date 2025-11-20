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
            $table->boolean('is_active')->default(true);
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // ======================
        // Table: dosen_pembimbing
        // ======================
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id('dosbing_id');
            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('jabatan', 50)->nullable();
            $table->timestamps();
        });

        // ======================
        // Table: mitra
        // ======================
        Schema::create('mitra', function (Blueprint $table) {
            $table->id('mitra_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('nama_mitra', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('bidang_usaha', 100)->nullable();
            $table->text('deskripsi')->nullable();
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
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->string('angkatan', 10)->nullable();
            $table->integer('semester')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_profile', 255)->nullable();
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();



            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        // ======================
        // Table: supervisor
        // ======================
        Schema::create('supervisor', function (Blueprint $table) {
            $table->id('supervisor_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('mitra_id');
            $table->string('nama_supervisor', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('mitra_id')->references('mitra_id')->on('mitra')->onDelete('cascade');
        });

        // ======================
        // Table: magang
        // ======================
        Schema::create('magang', function (Blueprint $table) {
            $table->id('magang_id');
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mitra_id')->nullable();
            // $table->unsignedBigInteger('supervisor_id')->nullable();
            $table->unsignedBigInteger('dosbing_id')->nullable();
            $table->year('tahun_ajaran');
            $table->integer('semester_magang');
            $table->integer('jumlah_magang_ke')->default(1);
            $table->string('role_magang', 100)->nullable();
            $table->text('jobdesk')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('periode_bulan')->default(5);
            $table->enum('status_magang', ['draft', 'berlangsung', 'selesai', 'ditolak'])->default('draft');
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

            // Relasi ke supervisor → set null kalau supervisor dihapus
            // $table->foreign('supervisor_id')
            //     ->references('supervisor_id')
            //     ->on('supervisor')
            //     ->nullOnDelete();
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

