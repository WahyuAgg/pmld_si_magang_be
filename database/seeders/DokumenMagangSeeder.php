<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magang;
use App\Models\DokumenMagang;
use Illuminate\Support\Str;

class DokumenMagangSeeder extends Seeder
{
    public function run(): void
    {
        $dokumenTypes = ['surat_penerimaan', 'pra_krs', 'laporan_magang', 'doc_penilaian_mitra'];

        $magangs = Magang::all();

        foreach ($magangs as $magang) {
            foreach ($dokumenTypes as $type) {
                DokumenMagang::create([
                    'magang_id' => $magang->magang_id,
                    'jenis_dokumen' => $type,
                    'nama_file' => $type . '_' . $magang->magang_id . '.pdf',
                    'path_file' => 'uploads/magang/' . $magang->magang_id . '/' . $type . '.pdf',
                    'ukuran_file' => rand(100, 1024) * 1024, // random 100KB - 1MB
                    'status_dokumen' => 'draft',
                    'keterangan' => 'Dokumen ' . Str::title(str_replace('_', ' ', $type)),
                    'uploaded_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
