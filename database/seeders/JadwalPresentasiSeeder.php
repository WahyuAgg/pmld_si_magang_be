<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalPresentasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('jadwal_presentasi')->insert([
            [
                'magang_id' => 1,
                'tanggal_presentasi' => Carbon::create(2025, 11, 10),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '10:00',
                'tempat' => 'Gedung A',
                'ruangan' => 'Ruang 101',
                'keterangan' => 'Presentasi akhir magang batch 1',
                'status' => 'terjadwal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 2,
                'tanggal_presentasi' => Carbon::create(2025, 11, 12),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '14:00',
                'tempat' => 'Gedung B',
                'ruangan' => 'Ruang 202',
                'keterangan' => 'Presentasi tengah magang batch 2',
                'status' => 'terjadwal',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 3,
                'tanggal_presentasi' => Carbon::create(2025, 11, 15),
                'waktu_mulai' => '10:00',
                'waktu_selesai' => '11:00',
                'tempat' => 'Gedung C',
                'ruangan' => 'Ruang 303',
                'keterangan' => 'Presentasi akhir magang batch 3',
                'status' => 'selesai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'magang_id' => 4,
                'tanggal_presentasi' => Carbon::create(2025, 11, 18),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '09:00',
                'tempat' => 'Aula Utama',
                'ruangan' => 'Aula 1',
                'keterangan' => 'Presentasi dibatalkan karena kendala teknis',
                'status' => 'dibatalkan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
