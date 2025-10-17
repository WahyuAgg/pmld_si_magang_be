<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Mitra;
use App\Models\Supervisor;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mitras = Mitra::all();
        $counter = 1;

        foreach ($mitras as $mitra) {
            for ($i = 1; $i <= 2; $i++) {
                // Buat dulu akun user supervisor
                $user = User::create([
                    'name' => "Supervisor {$mitra->nama_mitra} - $i",
                    'email' => "supervisor{$counter}@example.com",
                    'username' => "supervisor{$counter}",
                    'password' => Hash::make('password123'),
                    'role' => 'supervisor',
                    'is_active' => true,
                ]);

                // Buat data supervisor dan tautkan ke user + mitra
                Supervisor::create([
                    'user_id' => $user->user_id,
                    'mitra_id' => $mitra->mitra_id,
                    'nama_supervisor' => $user->name,
                    'jabatan' => $i === 1 ? 'Manager' : 'Staff Senior',
                    'email' => $user->email,
                    'no_hp' => '08' . rand(111111111, 999999999),
                ]);

                $counter++;
            }
        }
    }
}
