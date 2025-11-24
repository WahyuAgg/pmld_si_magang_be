<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Magang;
use App\Models\Dosbing;
use App\Models\Mitra;

class DashboardController extends Controller
{
    public function dashboardSummary()
    {
        // 1. Hitung jumlah magang aktif berdasarkan tahun ajaran terbaru
        $tahunAjaranTerbaru = Magang::latest('tahun_ajaran')->value('tahun_ajaran');
        $SemesterMagangTerbaru = Magang::latest('semester_magang')->value('semester_magang'); 


        if ($tahunAjaranTerbaru) {
            $jumlahMagangAktif = Magang::where('tahun_ajaran', $tahunAjaranTerbaru)
                                        // ->where('semester_magang', $SemesterMagangTerbaru)
                                        ->count();
        } else {
            $jumlahMagangAktif = 0;
        }

        // 2. Hitung mitra
        $jumlahMitra = Mitra::count();

        // 3. Hitung dosen pembimbing
        $jumlahDosbing = Dosbing::count();

        // Return JSON gabungan
        return response()->json([
            'jumlah_mahasiswa_aktif_magang' => $jumlahMagangAktif,
            'jumlah_mitra'        => $jumlahMitra,
            'jumlah_dosbing'      => $jumlahDosbing,
        ], 200);
    }
}

