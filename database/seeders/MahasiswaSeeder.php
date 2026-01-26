<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;



class MahasiswaSeeder extends Seeder
{
    public function run(): void
    {
        $angkatanList = [2022, 2023]; // 2 angkatan
        $counter = 1;

        foreach ($angkatanList as $angkatan) {
            for ($i = 1; $i <= 8; $i++) {
                // 1. Buat user dulu
                $user = User::create([
                    'username' => "mhs{$angkatan}{$i}",
                    'password' => Hash::make('password123'),
                    'role' => 'mahasiswa',
                ]);

                $faker = Faker::create();


                // 2. Buat mahasiswa terkait user
                Mahasiswa::create([
                    'user_id' => $user->user_id,
                    'nim' => "NIM{$angkatan}" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama' => $faker->name, // contoh: "Budi Santoso" atau "Rina Anggraini"
                    'angkatan' => $angkatan,
                ]);

                $counter++;
            }
        }
    }
}
