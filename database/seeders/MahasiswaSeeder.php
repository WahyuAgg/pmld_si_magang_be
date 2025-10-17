<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Hash;

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
                    'name' => "Mahasiswa {$angkatan} - {$i}",
                    'email' => "mhs{$angkatan}{$i}@example.com",
                    'username' => "mhs{$angkatan}{$i}",
                    'password' => Hash::make('password123'),
                    'role' => 'mahasiswa',
                    'is_active' => true,
                ]);

                // 2. Buat mahasiswa terkait user
                Mahasiswa::create([
                    'user_id' => $user->user_id,
                    'nim' => "NIM{$angkatan}" . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'nama' => $user->name,
                    'email' => $user->email,
                    'no_hp' => '08' . rand(111111111, 999999999),
                    'angkatan' => $angkatan,
                    'semester' => ($angkatan == 2022 ? 6 : 5),
                    'alamat' => "Alamat Mahasiswa {$angkatan}-{$i}",
                    'foto_profile' => null,
                    'status_aktif' => true,
                ]);

                $counter++;
            }
        }
    }
}
