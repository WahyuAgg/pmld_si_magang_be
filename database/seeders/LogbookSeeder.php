<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LogbookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('logbook')->insert([
            [
                'magang_id' => 1,
                'tanggal_kegiatan' => Carbon::create(2025, 10, 20),
                'kegiatan' => 'Mempelajari struktur organisasi dan alur kerja perusahaan',
                'deskripsi_kegiatan' => 'Mahasiswa melakukan orientasi awal mengenai SOP dan sistem kerja tim IT di perusahaan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 1,
                'tanggal_kegiatan' => Carbon::create(2025, 10, 21),
                'kegiatan' => 'Implementasi desain database untuk sistem inventori',
                'deskripsi_kegiatan' => 'Membuat ERD dan mengonversinya menjadi struktur tabel menggunakan MySQL.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 2,
                'tanggal_kegiatan' => Carbon::create(2025, 10, 22),
                'kegiatan' => 'Testing API internal perusahaan',
                'deskripsi_kegiatan' => 'Menggunakan Postman untuk menguji endpoint API terkait data karyawan dan proyek.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 3,
                'tanggal_kegiatan' => Carbon::create(2025, 10, 23),
                'kegiatan' => 'Pembuatan dokumentasi teknis aplikasi magang',
                'deskripsi_kegiatan' => 'Menulis dokumentasi API, endpoint, dan contoh response menggunakan Markdown.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 4,
                'tanggal_kegiatan' => Carbon::create(2025, 10, 24),
                'kegiatan' => 'Rapat evaluasi mingguan dengan supervisor',
                'deskripsi_kegiatan' => 'Mendiskusikan kemajuan proyek dan rencana kegiatan minggu berikutnya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
