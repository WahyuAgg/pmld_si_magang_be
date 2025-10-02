<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ======================
        // Table: penilaian_mitra
        // ======================
        Schema::create('penilaian_mitra', function (Blueprint $table) {
            $table->unsignedBigInteger('penilaian_id')->autoIncrement();
            $table->unsignedBigInteger('magang_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->primary(['penilaian_id', 'supervisor_id']);
        });


        // ======================
        // Table: dokumen_magang
        // ======================
        Schema::create('dokumen_magang', function (Blueprint $table) {
            $table->unsignedBigInteger('dokumen_id')->autoIncrement();
            $table->unsignedBigInteger('magang_id');
            $table->enum('jenis_dokumen', ['surat_penerimaan', 'pra_krs', 'laporan_magang']);
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->bigInteger('ukuran_file')->nullable();
            $table->enum('status_dokumen', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['dokumen_id', 'magang_id']);
        });

        // ======================
        // Table: dokumen_penilaian_mitra
        // ======================
        Schema::create('dokumen_penilaian_mitra', function (Blueprint $table) {
            $table->unsignedBigInteger('dokumen_penilaian_id')->autoIncrement();
            $table->unsignedBigInteger('magang_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('jenis_dokumen', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->primary(['dokumen_penilaian_id', 'magang_id', 'supervisor_id']);
        });

        // ======================
        // Table: jadwal_presentasi
        // ======================
        Schema::create('jadwal_presentasi', function (Blueprint $table) {
            $table->unsignedBigInteger('jadwal_id')->autoIncrement();
            $table->unsignedBigInteger('magang_id');
            $table->date('tanggal_presentasi');
            $table->string('waktu_mulai', 20);
            $table->string('waktu_selesai', 20);
            $table->string('tempat', 100)->nullable();
            $table->string('ruangan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->primary(['jadwal_id', 'magang_id']);
        });

        // ======================
        // Table: logbook_magang
        // ======================
        Schema::create('logbook_magang', function (Blueprint $table) {
            $table->unsignedBigInteger('logbook_id')->autoIncrement();
            $table->unsignedBigInteger('magang_id');
            $table->date('tanggal_kegiatan');
            $table->text('kegiatan');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->timestamps();

            $table->primary(['logbook_id', 'magang_id']);
        });

        // ======================
        // Table: foto_kegiatan
        // ======================
        Schema::create('foto_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('foto_id')->autoIncrement();
            $table->unsignedBigInteger('logbook_id');
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->primary(['foto_id', 'logbook_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('foto_kegiatan');
        Schema::dropIfExists('logbook_magang');
        Schema::dropIfExists('jadwal_presentasi');
        Schema::dropIfExists('dokumen_penilaian_mitra');
        Schema::dropIfExists('dokumen_magang');
        Schema::dropIfExists('progress_magang');
        Schema::dropIfExists('penilaian_mitra');
    }
};
