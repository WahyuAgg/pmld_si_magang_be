
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
            $table->enum('role', ['admin','mahasiswa','supervisor', 'dosbing']);
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
            $table->string('nama_mitra', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('bidang_usaha', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });





        // ======================
        // Table: mahasiswa
        // ======================
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->unsignedBigInteger('mahasiswa_id')->autoIncrement();
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

            $table->primary(['mahasiswa_id','user_id']);

            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade');
        });

        // ======================
        // Table: supervisor
        // ======================
        Schema::create('supervisor', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->autoIncrement();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('mitra_id');
            $table->string('nama_supervisor', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->timestamps();

            $table->primary(['supervisor_id','mitra_id']);
            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('mitra_id')->references('mitra_id')->on('mitra');
        });

        // ======================
        // Table: magang
        // ======================
        Schema::create('magang', function (Blueprint $table) {
            $table->unsignedBigInteger('magang_id')->autoIncrement();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('mitra_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->unsignedBigInteger('dosbing_id');
            $table->year('tahun_ajaran');
            $table->integer('semester_magang');
            $table->integer('jumlah_magang_ke')->default(1);
            $table->string('role_magang', 100)->nullable();
            $table->text('jobdesk')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('periode_bulan')->default(5);
            $table->enum('status_magang', ['draft','berlangsung','selesai','ditolak'])->default('draft');
            $table->timestamps();

            $table->primary(['magang_id','mahasiswa_id','mitra_id','dosbing_id']);

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('mahasiswa');
            $table->foreign('mitra_id')->references('mitra_id')->on('mitra');
            $table->foreign('dosbing_id')->references('dosbing_id')->on('dosen_pembimbing');
            $table->foreign('supervisor_id')->references('supervisor_id')->on('supervisor');

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

