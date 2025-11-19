<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class AngkatanController extends Controller
{
    public function show()
    {
        // Dua angkatan terakhir
        $angkatan = Mahasiswa::distinct()
            ->orderBy('angkatan', 'desc')
            ->take(2)
            ->pluck('angkatan')
            ->map(fn($a) => (int) $a);


        return response()->json([
            'angkatan_terakhir' => $angkatan
        ]);
    }

}
