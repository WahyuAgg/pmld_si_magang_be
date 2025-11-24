<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\NilaiMitra;
use App\Models\Magang;
use Illuminate\Support\Str;


class NilaiMitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        $magangList = Magang::all(); // ambil semua data magang

        foreach ($magangList as $magang) {
            NilaiMitra::create([
                'magang_id' => $magang->magang_id,
                'nilai_teknis' => rand(70, 100),
                'nilai_profesionalisme_etika' => rand(70, 100),
                'nilai_komunikasi_presentasi' => rand(70, 100),
                'nilai_proyek_pengalaman_industri' => rand(70, 100),
                'keterangan' => 'Penilaian otomatis oleh Seeder',
                'supervisor' => 'Supervisor ' . Str::random(5),
                'jabatan_supervisor' => 'Manager',
            ]);
        }
    }
    // public function run(): void
    // {
    //     DB::table('penilaian_mitra')->insert([
    //         [
    //             'magang_id' => 1,
    //             'nilai_teknis' => 88.50,
    //             'nilai_profesionalisme_etika' => 90.00,
    //             'nilai_komunikasi_presentasi' => 85.25,
    //             'nilai_proyek_pengalaman_industri' => 87.75,
    //             'keterangan' => 'Mahasiswa menunjukkan kemampuan teknis dan etika kerja yang sangat baik.',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'magang_id' => 2,
    //             'nilai_teknis' => 80.00,
    //             'nilai_profesionalisme_etika' => 78.50,
    //             'nilai_komunikasi_presentasi' => 82.00,
    //             'nilai_proyek_pengalaman_industri' => 79.75,
    //             'keterangan' => 'Cukup baik dalam implementasi proyek, perlu peningkatan dalam komunikasi tim.',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'magang_id' => 3,
    //             'nilai_teknis' => 92.25,
    //             'nilai_profesionalisme_etika' => 93.00,
    //             'nilai_komunikasi_presentasi' => 90.50,
    //             'nilai_proyek_pengalaman_industri' => 94.00,
    //             'keterangan' => 'Sangat unggul dalam semua aspek, direkomendasikan untuk proyek lanjutan.',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'magang_id' => 4,
    //             'nilai_teknis' => 75.50,
    //             'nilai_profesionalisme_etika' => 77.25,
    //             'nilai_komunikasi_presentasi' => 74.00,
    //             'nilai_proyek_pengalaman_industri' => 76.50,
    //             'keterangan' => 'Perlu peningkatan dalam disiplin kerja dan komunikasi dengan supervisor.',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //         [
    //             'magang_id' => 5,
    //             'nilai_teknis' => 85.00,
    //             'nilai_profesionalisme_etika' => 88.00,
    //             'nilai_komunikasi_presentasi' => 86.50,
    //             'nilai_proyek_pengalaman_industri' => 87.00,
    //             'keterangan' => 'Kinerja stabil dan komunikasi yang efektif selama magang.',
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ],
    //     ]);
    // }
}
