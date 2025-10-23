<?php

namespace App\Http\Controllers;

use App\Models\NilaiMitra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NilaiMitraController extends Controller
{
    /**
     * Tampilkan daftar penilaian mitra (dengan filter opsional).
     */
    public function index(Request $request)
    {
        $query = NilaiMitra::with(['magang']);

        // 🔍 Filter by magang_id
        if ($request->query('magang_id')) {
            $query->where('magang_id', $request->query('magang_id'));
        }


        // 🔍 Filter by minimum nilai_teknis
        if ($request->query('min_teknis')) {
            $query->where('nilai_teknis', '>=', $request->query('min_teknis'));
        }

        // 🔍 Filter by maximum nilai_teknis
        if ($request->query('max_teknis')) {
            $query->where('nilai_teknis', '<=', $request->query('max_teknis'));
        }

        // 🔍 Filter by range profesionalisme
        if ($request->query('range_profesional')) {
            [$min, $max] = explode('-', $request->query('range_profesional'));
            $query->whereBetween('nilai_profesionalisme_etika', [(int) $min, (int) $max]);
        }

        // 🔍 Filter by keyword (cari di keterangan)
        if ($request->query('q')) {
            $search = $request->query('q');
            $query->where('keterangan', 'like', "%{$search}%");
        }

        // 🔁 Sorting dinamis
        $sortBy = $request->query('sort_by', 'penilaian_id');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 📄 Pagination
        $perPage = (int) $request->query('per_page', 20);
        $data = $query->paginate($perPage);

        return response()->json($data, 200);
    }

    /**
     * Simpan penilaian mitra baru.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'nilai_teknis' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_profesionalisme_etika' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_komunikasi_presentasi' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_proyek_pengalaman_industri' => ['required', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nilaiMitra = NilaiMitra::create($validator->validated());

        return response()->json([
            'message' => 'Penilaian mitra berhasil dibuat',
            'data' => $nilaiMitra->load('magang'),
        ], 201);
    }

    /**
     * Tampilkan detail penilaian mitra.
     */
    public function show($id)
    {
        $nilaiMitra = NilaiMitra::with('magang')->findOrFail($id);

        return response()->json($nilaiMitra, 200);
    }

    /**
     * Update penilaian mitra.
     */
    public function update(Request $request, $id)
    {
        $nilaiMitra = NilaiMitra::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nilai_teknis' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'nilai_profesionalisme_etika' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'nilai_komunikasi_presentasi' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'nilai_proyek_pengalaman_industri' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'keterangan' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nilaiMitra->update($validator->validated());

        return response()->json([
            'message' => 'Penilaian mitra berhasil diperbarui',
            'data' => $nilaiMitra->fresh()->load('magang'),
        ], 200);
    }

    /**
     * Hapus penilaian mitra.
     */
    public function destroy($id)
    {
        $nilaiMitra = NilaiMitra::findOrFail($id);
        $nilaiMitra->delete();

        return response()->json([
            'message' => 'Penilaian mitra berhasil dihapus'
        ], 200);
    }
}
