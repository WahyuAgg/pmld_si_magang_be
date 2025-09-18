
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
        // Table: perusahaan
        // ======================
        Schema::create('perusahaan', function (Blueprint $table) {
            $table->id('perusahaan_id');
            $table->string('nama_perusahaan', 150);
            $table->text('alamat')->nullable();
            $table->string('no_telp', 15)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('bidang_usaha', 100)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // ======================
        // Table: tahun_ajaran
        // ======================
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id('tahun_ajaran_id');
            $table->string('nama_tahun_ajaran', 20);
            $table->enum('semester', ['ganjil','genap']);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        // ======================
        // Table: users
        // ======================
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('user_type', ['admin','mahasiswa','mitra']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // ======================
        // Table: variabel_penilaian
        // ======================
        Schema::create('variabel_penilaian', function (Blueprint $table) {
            $table->id('variabel_id');
            $table->string('nama_variabel', 100);
            $table->text('deskripsi')->nullable();
            $table->integer('bobot')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();
        });

        // ======================
        // Table: data_admin
        // ======================
        Schema::create('data_admin', function (Blueprint $table) {
            $table->id('admin_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('user_id')->on('users');
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
        // Table: supervisor_perusahaan
        // ======================
        Schema::create('supervisor_perusahaan', function (Blueprint $table) {
            $table->unsignedBigInteger('supervisor_id')->autoIncrement();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('perusahaan_id');
            $table->string('nama_supervisor', 100);
            $table->string('jabatan', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('no_hp', 15)->nullable();
            $table->timestamps();

            $table->primary(['supervisor_id','perusahaan_id']);
            $table->foreign('user_id')->references('user_id')->on('users')->nullOnDelete();
            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('perusahaan');
        });

        // ======================
        // Table: magang
        // ======================
        Schema::create('magang', function (Blueprint $table) {
            $table->unsignedBigInteger('magang_id')->autoIncrement();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->unsignedBigInteger('perusahaan_id');
            $table->unsignedBigInteger('dosbing_id');
            $table->unsignedBigInteger('tahun_ajaran_id');
            $table->integer('semester_magang');
            $table->integer('jumlah_magang_ke')->default(1);
            $table->string('role_magang', 100)->nullable();
            $table->text('jobdesk')->nullable();
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->integer('periode_bulan')->default(5);
            $table->enum('status_magang', ['draft','berlangsung','selesai','ditolak'])->default('draft');
            $table->timestamps();

            $table->primary(['magang_id','mahasiswa_id','perusahaan_id','dosbing_id','tahun_ajaran_id']);
            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('mahasiswa');
            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('perusahaan');
            $table->foreign('dosbing_id')->references('dosbing_id')->on('dosen_pembimbing');
            $table->foreign('tahun_ajaran_id')->references('tahun_ajaran_id')->on('tahun_ajaran');
        });

        // 🚨 lanjutkan untuk penilaian_mitra, progress_magang, dokumen_magang, dll.
    }

    public function down(): void
    {
        Schema::dropIfExists('magang');
        Schema::dropIfExists('supervisor_perusahaan');
        Schema::dropIfExists('mahasiswa');
        Schema::dropIfExists('data_admin');
        Schema::dropIfExists('variabel_penilaian');
        Schema::dropIfExists('users');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('perusahaan');
        Schema::dropIfExists('dosen_pembimbing');
    }
};

