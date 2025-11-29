<?php

namespace App\Http\Controllers;

use App\Models\DokumenMagang;
use App\Models\NilaiMitra;
use Illuminate\Http\Request;
use App\Models\Magang;
use App\Models\Dosbing;
use App\Models\Logbook;
use App\Models\Mitra;


class DashboardController extends Controller
{
    public function fetchDashboardDataAdmin()
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
            'jumlah_mitra' => $jumlahMitra,
            'jumlah_dosbing' => $jumlahDosbing,
        ], 200);
    }

    public function fetchDashboardDataMitra()
    {
        // Hitung jumlah mahasiswa magang untuk mitra yang sedang login
        $mitraId = auth()->user()->mitra->mitra_id;

        $jumlahMahasiswaMagangByMitra = Magang::where('mitra_id', $mitraId)
            ->distinct('mahasiswa_id')
            ->count('mahasiswa_id');

        $jumlahPenilaian = NilaiMitra::whereHas('magang', function ($query) use ($mitraId) {
            $query->where('mitra_id', $mitraId);
        })->count();

        $jumlahMagang = Magang::where('mitra_id', $mitraId)->count();

        return response()->json([
            'jumlah_mahasiswa_magang_by_mitra' => $jumlahMahasiswaMagangByMitra,
            'jumlah_penilaian' => $jumlahPenilaian,
            'jumlah_magang' => $jumlahMagang,
        ], 200);
    }


    public function fetchDashboardDataMahasiswa()
{
    // 1. Ambil Mahasiswa ID dari Auth
    // Pastikan relasi 'mahasiswa' ada di model User
    $user = auth()->user(); 
    if (!$user || !$user->mahasiswa) {
        return response()->json(['message' => 'Data mahasiswa tidak ditemukan'], 404);
    }
    $mahasiswaId = $user->mahasiswa->mahasiswa_id;

    // ==========================================
    // LOGIKA MAGANG 1 (SEMESTER 6)
    // ==========================================
    
    // Inisialisasi default value agar frontend tidak error jika data null
    $sumMagang1 = [
        'persiapan_magang' => 0,
        'pelaksanaan_magang' => 0,
        'laporan_magang' => 0
    ];

    // Ambil data Magang 1
    $magang1 = Magang::where('mahasiswa_id', $mahasiswaId)
                     ->where('semester_magang', 6)
                     ->first(); // Penting: gunakan first()

    if ($magang1) {
        // Cek dokumen (return true/false)
        $hasPraKrs = DokumenMagang::where('magang_id', $magang1->magang_id)->where('jenis_dokumen', 'doc_pra_krs')->exists();
        $hasSuratTerima = DokumenMagang::where('magang_id', $magang1->magang_id)->where('jenis_dokumen', 'doc_surat_penerimaan')->exists();
        $hasLogbook = Logbook::where('magang_id', $magang1->magang_id)->exists();
        // Asumsi laporan akhir juga ada di tabel dokumen
        $hasLaporan = DokumenMagang::where('magang_id', $magang1->magang_id)->where('jenis_dokumen', 'laporan_akhir')->exists();

        // Hitung Skor Persiapan
        if ($hasPraKrs) $sumMagang1['persiapan_magang'] += 50;
        if ($hasSuratTerima) $sumMagang1['persiapan_magang'] += 50;

        // Hitung Skor Pelaksanaan
        if ($hasLogbook) $sumMagang1['pelaksanaan_magang'] = 100;

        // Hitung Skor Laporan
        if ($hasLaporan) $sumMagang1['laporan_magang'] = 100;
    }

    // ==========================================
    // LOGIKA MAGANG 2 (SEMESTER 7)
    // ==========================================

    // Inisialisasi default value
    $sumMagang2 = [
        'persiapan_magang' => 0,
        'pelaksanaan_magang' => 0,
        'laporan_magang' => 0
    ];

    // Ambil data Magang 2
    $magang2 = Magang::where('mahasiswa_id', $mahasiswaId)
                     ->where('semester_magang', 7) // Filter Semester 7
                     ->first();

    if ($magang2) {
        // Cek dokumen menggunakan ID magang2
        $hasPraKrs = DokumenMagang::where('magang_id', $magang2->magang_id)->where('jenis_dokumen', 'doc_pra_krs')->exists();
        $hasSuratTerima = DokumenMagang::where('magang_id', $magang2->magang_id)->where('jenis_dokumen', 'doc_surat_penerimaan')->exists();
        $hasLogbook = Logbook::where('magang_id', $magang2->magang_id)->exists();
        $hasLaporan = DokumenMagang::where('magang_id', $magang2->magang_id)->where('jenis_dokumen', 'laporan_akhir')->exists();

        // Hitung Skor Persiapan (Logic sama dengan magang 1)
        if ($hasPraKrs) $sumMagang2['persiapan_magang'] += 50;
        if ($hasSuratTerima) $sumMagang2['persiapan_magang'] += 50;

        // Hitung Skor Pelaksanaan
        if ($hasLogbook) $sumMagang2['pelaksanaan_magang'] = 100;

        // Hitung Skor Laporan
        if ($hasLaporan) $sumMagang2['laporan_magang'] = 100;
    }

    // ==========================================
    // RETURN DATA
    // ==========================================
    
    return response()->json([
        'status' => 'success',
        'data' => [
            'magang_semester_6' => $sumMagang1,
            'magang_semester_7' => $sumMagang2,
        ]
    ]);
}
}

