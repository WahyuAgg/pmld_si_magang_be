<?php

namespace App\Http\Controllers;

use App\Models\Logbook;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Http\Request;

class LogbookController extends Controller
{
    /**
     * Tampilkan daftar logbook (dengan filter dan pagination).
     */
    public function index(Request $request)
    {
        $query = Logbook::with(['fotoKegiatan', 'magang:magang_id,mahasiswa_id,semester_magang']);

        // 🔐 Filter otomatis untuk role mahasiswa
        $user = auth()->user();

        if ($user && $user->mahasiswa) {
            // Jika user adalah mahasiswa, filter berdasarkan mahasiswa_id mereka
            $mahasiswaId = $user->mahasiswa->mahasiswa_id;

            $query->whereHas('magang', function ($q) use ($mahasiswaId) {
                $q->where('mahasiswa_id', $mahasiswaId);
            });
        }
        // Jika bukan mahasiswa (admin, pembimbing, dll), tampilkan semua logbook

        // 🔎 Filter berdasarkan magang_id (opsional)
        if ($magangId = $request->query('magang_id')) {
            $query->where('magang_id', $magangId);
        }

        // 🔎 Filter tanggal kegiatan (opsional)
        if ($tanggal = $request->query('tanggal')) {
            $query->whereDate('tanggal_kegiatan', $tanggal);
        }

        $logbooks = $query->orderByDesc('tanggal_kegiatan')->get();

        return response()->json($logbooks, 200);
    }

    /**
     * Tampilkan detail logbook berdasarkan id.
     */
    public function show($id)
    {
        $logbook = Logbook::with(['fotoKegiatan'])->findOrFail($id);

        return response()->json($logbook, 200);
    }

    /**
     * Simpan logbook baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'tanggal_kegiatan' => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'max:255'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $logbook = Logbook::create($data);

        return response()->json([
            'message' => 'Logbook berhasil dibuat',
            'data' => $logbook->load(['magang', 'fotoKegiatan']),
        ], 201);
    }

    /**
     * Update logbook.
     */
    public function update(Request $request, $id)
    {
        $logbook = Logbook::findOrFail($id);

        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'tanggal_kegiatan' => ['required', 'date'],
            'kegiatan' => ['required', 'string', 'max:255'],
            'deskripsi_kegiatan' => ['nullable', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $logbook->update($data);

        return response()->json([
            'message' => 'Logbook berhasil diperbarui',
            'data' => $logbook->fresh()->load(['magang', 'fotoKegiatan']),
        ], 200);
    }

    /**
     * Hapus logbook.
     */
    public function destroy($id)
    {
        $logbook = Logbook::findOrFail($id);

        $logbook->delete();

        return response()->json([
            'message' => 'Logbook berhasil dihapus',
        ], 200);
    }

    public function getLogbookByMagang($id)
    {

    }
}
