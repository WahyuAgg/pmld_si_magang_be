<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class AngkatanController extends Controller
{
    public function show()
    {
        $angkatan = Mahasiswa::distinct()
            ->orderBy('angkatan', 'desc')
            ->pluck('angkatan')
            ->map(fn($a) => (int) $a)
            ->values();


        return response()->json([
            'angkatan_terakhir' => $angkatan
        ]);
    }

}
