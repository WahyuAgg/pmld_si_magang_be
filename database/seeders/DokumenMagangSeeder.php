<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Magang;
use App\Models\DokumenMagang;
use Illuminate\Support\Str;

use Illuminate\Support\Facades\Storage;

class DokumenMagangSeeder extends Seeder
{
    public function run(): void
    {
        $dokumenTypes = [
            'doc_surat_penerimaan',
            'doc_pra_krs',
            'doc_laporan_magang',
            'doc_penilaian_mitra'
        ];

        $magangs = Magang::all();

        foreach ($magangs as $magang) {
            foreach ($dokumenTypes as $type) {

                $namaFile = $type . '_' . $magang->magang_id . '.pdf';

                // Path asal dan tujuan
                $sourceFile = 'public/dokumen_magang/sampel_doc.pdf';
                $destFile   = 'public/dokumen_magang/' . $namaFile;

                // Copy file fisik
                if (Storage::exists($sourceFile)) {
                    Storage::copy($sourceFile, $destFile);
                }

                DokumenMagang::create([
                    'magang_id'       => $magang->magang_id,
                    'jenis_dokumen'   => $type,
                    'nama_file'       => $namaFile,
                    'path_file'       => '/storage/dokumen_magang/' . $namaFile,
                    'ukuran_file'     => Storage::exists($destFile) ? Storage::size($destFile) : rand(100, 1024) * 1024,
                    'status_dokumen'  => 'draft',
                    'keterangan'      => 'Dokumen ' . Str::title(str_replace('_', ' ', $type)),
                    'uploaded_at'     => now(),
                    'updated_at'      => now(),
                ]);
            }
        }
    }
}
