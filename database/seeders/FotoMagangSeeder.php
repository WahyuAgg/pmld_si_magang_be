<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FotoMagangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('foto_kegiatan')->insert([
            [
                'logbook_id' => 1,
                'nama_file' => 'orientasi_perusahaan.jpg',
                'path_file' => 'storage/foto_kegiatan/orientasi_perusahaan.jpg',
                'keterangan' => 'Kegiatan orientasi hari pertama di perusahaan',
                'uploaded_at' => Carbon::create(2025, 10, 20, 9, 0, 0),
            ],
            [
                'logbook_id' => 1,
                'nama_file' => 'pembuatan_erd.png',
                'path_file' => 'storage/foto_kegiatan/pembuatan_erd.png',
                'keterangan' => 'Mahasiswa membuat diagram ERD untuk sistem inventori',
                'uploaded_at' => Carbon::create(2025, 10, 21, 10, 30, 0),
            ],
            [
                'logbook_id' => 2,
                'nama_file' => 'testing_api.jpg',
                'path_file' => 'storage/foto_kegiatan/testing_api.jpg',
                'keterangan' => 'Proses pengujian endpoint API menggunakan Postman',
                'uploaded_at' => Carbon::create(2025, 10, 22, 14, 15, 0),
            ],
            [
                'logbook_id' => 3,
                'nama_file' => 'dokumentasi_api.png',
                'path_file' => 'storage/foto_kegiatan/dokumentasi_api.png',
                'keterangan' => 'Mahasiswa menulis dokumentasi API di Notion',
                'uploaded_at' => Carbon::create(2025, 10, 23, 11, 45, 0),
            ],
            [
                'logbook_id' => 4,
                'nama_file' => 'rapat_evaluasi.jpg',
                'path_file' => 'storage/foto_kegiatan/rapat_evaluasi.jpg',
                'keterangan' => 'Rapat evaluasi mingguan bersama supervisor magang',
                'uploaded_at' => Carbon::create(2025, 10, 24, 13, 0, 0),
            ],
        ]);
    }
}
