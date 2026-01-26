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

        for ($i = 1; $i <= 16; $i++) {

            $email = "mitra$i@example.com";
            $username = Str::before($email, '@');

            // 🔹 Buat user untuk mitra
            $user = User::create([
                'email' => $email,
                'username' => $username,
                'password' => Hash::make($username), // password = username
                'role' => 'mitra',
            ]);

            // 🔹 Buat data mitra
            Mitra::create([
                'nama_mitra' => "PT Mitra Sejahtera $i",
                'user_id' => $user->user_id,
                'email' => $email,
                'narahubung' => "Nama narahubung $i",
            ]);
        }
    }
}
