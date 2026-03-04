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
            $table->unsignedBigInteger('magang_id')->unique();
            $table->decimal('nilai_teknis', 5, 2);
            $table->decimal('nilai_profesionalisme_etika', 5, 2);
            $table->decimal('nilai_komunikasi_presentasi', 5, 2);
            $table->decimal('nilai_proyek_pengalaman_industri', 5, 2);
            $table->text('keterangan')->nullable();
            $table->string('supervisor', 255);
            $table->string('jabatan_supervisor', 255);
            $table->string('nama_file');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('magang_id')->references('magang_id')->on('magang')->onDelete('cascade');
        });


        // ======================
        // Table: dokumen_magang
        // ======================
        Schema::create('dokumen_magang', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->unsignedBigInteger('magang_id');
            $table->enum('jenis_dokumen', ['doc_surat_penerimaan', 'doc_pra_krs']);
            $table->string('nama_file', 255);
            $table->string('file_path', 500);
            $table->bigInteger('ukuran_file')->nullable();
            $table->timestamp('uploaded_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('magang_id')->references('magang_id')->on('magang')->onDelete('cascade');

        });


        // ======================
        // Table: Laporan
        // ======================
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->unsignedBigInteger('magang_id')->unique();
            $table->foreign('magang_id')->references('magang_id')->on('magang')->onDelete('cascade');
            $table->string('nama_file')->nullable(); 
            $table->string('file_path');
            $table->timestamps();
        });


        // ======================
        // Table: jadwal_presentasi
        // ======================
        Schema::create('jadwal_presentasi', function (Blueprint $table) {
            $table->id('jadwal_id');
            $table->date('tanggal_presentasi')->nullable();
            $table->string('waktu_mulai', 20)->nullable();
            $table->string('waktu_selesai', 20)->nullable();
            $table->string('tempat', 100)->nullable();
            $table->string('ruangan', 50)->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();
        });


        // ======================
        // Table: logbook_magang
        // ======================
        Schema::create('logbook', function (Blueprint $table) {
            $table->id('logbook_id');
            $table->unsignedBigInteger('magang_id')->unique();
            $table->text('kegiatan');
            $table->timestamps();

            $table->foreign('magang_id')->references('magang_id')->on('magang')->onDelete('cascade');
        });


        // ======================
        // Table: foto_kegiatan
        // ======================
        Schema::create('foto_kegiatan', function (Blueprint $table) {
            $table->id('foto_id');
            $table->unsignedBigInteger('logbook_id');
            $table->string('nama_file', 255);
            $table->string('file_path', 500);
            $table->timestamps();

            $table->foreign('logbook_id')->references('logbook_id')->on('logbook')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_mitra');
        Schema::dropIfExists('dokumen_magang');
        Schema::dropIfExists('laporan');
        Schema::dropIfExists('jadwal_presentasi');
        Schema::dropIfExists('logbook');
        Schema::dropIfExists('foto_kegiatan');
    }
};
