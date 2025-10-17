<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magang;
use App\Models\Mahasiswa;

class MagangSeeder extends Seeder
{
    public function run(): void
    {
        $mahasiswaList = Mahasiswa::all();

        foreach ($mahasiswaList as $mhs) {
            // tentukan tahun ajaran berdasarkan angkatan
            $tahunAjaran = $mhs->angkatan == 2022 ? 2025 : 2026;

            // ===== Magang ke-1 (semester 6) =====
            Magang::create([
                'mahasiswa_id' => $mhs->mahasiswa_id,
                'mitra_id' => rand(1, 16), // random mitra id
                'dosbing_id' => rand(1, 20), // random dosbing id
                'supervisor_id' => rand(1, 32), // random dosbing id
                'tahun_ajaran' => $tahunAjaran,
                'semester_magang' => 6,
                'jumlah_magang_ke' => 1,
                'role_magang' => 'Software Engineer Intern',
                'jobdesk' => 'Membantu pengembangan aplikasi berbasis web dan mobile.',
                'tanggal_mulai' => $tahunAjaran . '-02-01',
                'tanggal_selesai' => $tahunAjaran . '-07-31',
                'periode_bulan' => 6,
                'status_magang' => $mhs->angkatan == 2022 ? 'selesai' : 'berlangsung',
            ]);

            // ===== Magang ke-2 (semester 7) =====
            Magang::create([
                'mahasiswa_id' => $mhs->mahasiswa_id,
                'mitra_id' => rand(1, 16), // random mitra id
                'dosbing_id' => rand(1, 20), // random dosbing id
                'supervisor_id' => rand(1, 32), // random dosbing id
                'tahun_ajaran' => $tahunAjaran,
                'semester_magang' => 7,
                'jumlah_magang_ke' => 2,
                'role_magang' => 'Backend Developer Intern',
                'jobdesk' => 'Membangun API dan mengelola database untuk aplikasi perusahaan.',
                'tanggal_mulai' => $tahunAjaran . '-08-01',
                'tanggal_selesai' => $tahunAjaran . '-12-31',
                'periode_bulan' => 5,
                'status_magang' => $mhs->angkatan == 2022 ? 'selesai' : 'draft',
            ]);
        }
    }
}
