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
            $table->id('penilaian_id');
            $table->unsignedBigInteger('magang_id');
            $table->decimal('nilai', 5, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            $table->foreign('magang_id')->references('magang_id')->on('magang');
        });


        // ======================
        // Table: dokumen_magang
        // ======================
        Schema::create('dokumen_magang', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->unsignedBigInteger('magang_id');
            $table->enum('jenis_dokumen', ['doc_surat_penerimaan', 'doc_pra_krs', 'doc_laporan_magang', 'doc_penilaian_mitra']);
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->bigInteger('ukuran_file')->nullable();
            $table->enum('status_dokumen', ['draft', 'submitted', 'approved', 'rejected'])->default('draft');
            $table->text('keterangan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('magang_id')->references('magang_id')->on('magang')->onDelete('cascade');

        });

        // ======================
        // Table: dokumen_penilaian_mitra
        // ======================
        Schema::create('dokumen_penilaian_mitra', function (Blueprint $table) {
            $table->id('dokumen_penilaian_id');
            $table->unsignedBigInteger('magang_id');
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('jenis_dokumen', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->foreign('magang_id')->references('magang_id')->on('magang');

        });

        // ======================
        // Table: jadwal_presentasi
        // ======================
        Schema::create('jadwal_presentasi', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->unsignedBigInteger('magang_id')->nullable();
            $table->date('tanggal_presentasi')->nullable();
            $table->string('waktu_mulai', 20)->nullable();
            $table->string('waktu_selesai', 20)->nullable();
            $table->string('tempat', 100)->nullable();
            $table->string('ruangan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();

            $table->unique(['jadwal_id', 'magang_id']);

            $table->foreign('magang_id')->references('magang_id')->on('magang');
        });

        // ======================
        // Table: logbook_magang
        // ======================
        Schema::create('logbook', function (Blueprint $table) {
            $table->id('logbook_id');
            $table->unsignedBigInteger('magang_id');
            $table->date('tanggal_kegiatan');
            $table->text('kegiatan');
            $table->text('deskripsi_kegiatan')->nullable();
            $table->timestamps();

            $table->unique(['logbook_id', 'magang_id']);

            $table->foreign('magang_id')->references('magang_id')->on('magang');

        });

        // ======================
        // Table: foto_kegiatan
        // ======================
        Schema::create('foto_kegiatan', function (Blueprint $table) {
            $table->id('foto_id');
            $table->unsignedBigInteger('logbook_id');
            $table->string('nama_file', 255);
            $table->string('path_file', 500);
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('uploaded_at')->useCurrent();

            $table->foreign('logbook_id')->references('logbook_id')->on('logbook');
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
