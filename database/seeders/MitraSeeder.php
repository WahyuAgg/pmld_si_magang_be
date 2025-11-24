<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mitra;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

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

            $email = "mitra$i@example.com";
            $username = Str::before($email, '@');

            // 🔹 Buat user untuk mitra
            $user = User::create([
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($username), // password = username
                'role' => 'mitra',
                'is_active' => true,
            ]);

            // 🔹 Buat data mitra
            Mitra::create([
                'nama_mitra' => "PT Mitra Sejahtera $i",
                'user_id' => $user->user_id,
                'alamat' => "Jl. Contoh Alamat No.$i, Kota Contoh",
                'no_telp' => '021' . rand(1000000, 9999999),
                'email' => $email,
                'narahubung' => "Nama narahubung $i",
                'website' => "https://www.mitra$i.com",
                'bidang_usaha' => $bidangUsaha[array_rand($bidangUsaha)],
                'deskripsi' => "PT Mitra Sejahtera $i bergerak di bidang {$bidangUsaha[array_rand($bidangUsaha)]}.",
            ]);
        }
    }
}
