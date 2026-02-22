<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logbook;
use App\Models\Magang;
use Carbon\Carbon;

class LogbookSeeder extends Seeder
{
    public function run()
    {
        $magangList = Magang::all(); // ambil semua data magang

        foreach ($magangList as $magang) {

            // jumlah log yg ingin dibuat per magang
            $jumlahLog = 1;

            for ($i = 0; $i < $jumlahLog; $i++) {
                Logbook::create([
                    'magang_id' => $magang->magang_id,
                    'kegiatan' => "Kegiatan ke-" . ($i + 1),
                ]);
            }
        }
    }
}
