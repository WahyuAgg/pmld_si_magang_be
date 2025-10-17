<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;

class MitraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bidangUsaha = [
            'Teknologi Informasi',
            'Kesehatan',
            'Manufaktur',
            'Pendidikan',
            'Perbankan',
            'Pariwisata',
            'Transportasi',
            'Retail',
        ];

        for ($i = 1; $i <= 16; $i++) {
            Mitra::create([
                'nama_mitra' => "PT Mitra Sejahtera $i",
                'alamat' => "Jl. Contoh Alamat No.$i, Kota Contoh",
                'no_telp' => '021' . rand(1000000, 9999999),
                'email' => "mitra$i@example.com",
                'website' => "https://www.mitra$i.com",
                'bidang_usaha' => $bidangUsaha[array_rand($bidangUsaha)],
                'deskripsi' => "PT Mitra Sejahtera $i bergerak di bidang {$bidangUsaha[array_rand($bidangUsaha)]}.",
            ]);
        }
    }
}
