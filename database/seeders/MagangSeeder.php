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
                'semester_magang' => 6,
                'role_magang' => 'Software Engineer Intern',
                'jobdesk' => 'Membantu pengembangan aplikasi berbasis web dan mobile.',
                'periode_bulan' => 6,
            ]);

            // ===== Magang ke-2 (semester 7) =====
            Magang::create([
                'mahasiswa_id' => $mhs->mahasiswa_id,
                'mitra_id' => rand(1, 16), // random mitra id
                'dosbing_id' => rand(1, 20), // random dosbing id
                'semester_magang' => 7,
                'role_magang' => 'Backend Developer Intern',
                'jobdesk' => 'Membangun API dan mengelola database untuk aplikasi perusahaan.',
                'periode_bulan' => 5,
            ]);
        }
    }
}
