<?php

namespace App\Http\Controllers;

use App\Models\NilaiMitra;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class NilaiMitraController extends Controller
{
    /**
     * Tampilkan semua penilaian mitra
     */
    public function index(Request $request)
    {
        $query = NilaiMitra::with(['magang', 'supervisor']);

        // Filter by magang_id
        if ($magangId = $request->query('magang_id')) {
            $query->where('magang_id', $magangId);
        }

        // Filter by supervisor_id
        if ($supervisorId = $request->query('supervisor_id')) {
            $query->where('supervisor_id', $supervisorId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $data = $query->latest('penilaian_id')->paginate($perPage);

        return response()->json($data, 200);
    }

    /**
     * Detail penilaian mitra
     */
    public function show($id)
    {
        $nilai = NilaiMitra::with(['magang', 'supervisor'])->findOrFail($id);
        return response()->json($nilai, 200);
    }

    /**
     * Tambah penilaian mitra
     */
    public function store(Request $request)
    {
        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'nilai' => ['required', 'numeric', 'between:0,100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nilai = NilaiMitra::create($request->all());

        return response()->json([
            'message' => 'Penilaian mitra berhasil ditambahkan',
            'data' => $nilai->load(['magang', 'supervisor']),
        ], 201);
    }

    /**
     * Update penilaian mitra
     */
    public function update(Request $request, $id)
    {
        $nilai = NilaiMitra::findOrFail($id);

        $rules = [
            'nilai' => ['nullable', 'numeric', 'between:0,100'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $nilai->update($request->only(['nilai', 'keterangan']));

        return response()->json([
            'message' => 'Penilaian mitra berhasil diperbarui',
            'data' => $nilai->fresh()->load(['magang', 'supervisor']),
        ], 200);
    }

    /**
     * Hapus penilaian mitra
     */
    public function destroy($id)
    {
        $nilai = NilaiMitra::findOrFail($id);
        $nilai->delete();

        return response()->json([
            'message' => 'Penilaian mitra berhasil dihapus',
        ], 200);
    }
}
