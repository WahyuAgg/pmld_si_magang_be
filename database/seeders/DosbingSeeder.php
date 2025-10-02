<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dosbing;

class DosbingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            Dosbing::create([
                'nip' => '1980' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'nama' => "Dosen Pembimbing $i",
                'email' => "dosbing$i@example.com",
                'no_hp' => '08' . rand(111111111, 999999999),
                'jabatan' => $i % 2 === 0 ? 'Lektor' : 'Asisten Ahli', // contoh variasi jabatan
            ]);
        }
    }
}
