<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class MitraController extends Controller
{
     /**
     * Tampilkan daftar mitra (dengan pagination dan pencarian sederhana).
     */
    public function index(Request $request)
    {
        $query = Mitra::with(['supervisor', 'magang']);

        // Pencarian: nama_perusahaan atau bidang_usaha
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_perusahaan', 'like', "%{$search}%")
                  ->orWhere('bidang_usaha', 'like', "%{$search}%");
            });
        }

        // Filter bidang usaha (opsional)
        if ($bidang = $request->query('bidang_usaha')) {
            $query->where('bidang_usaha', $bidang);
        }

        $perPage = (int) $request->query('per_page', 15);
        $mitra = $query->orderBy('nama_perusahaan')->paginate($perPage);

        return response()->json($mitra, 200);
    }

    /**
     * Tampilkan detail mitra berdasarkan id (perusahaan_id).
     */
    public function show($id)
    {
        $mitra = Mitra::with(['supervisor', 'magang'])->findOrFail($id);

        return response()->json($mitra, 200);
    }

    /**
     * Simpan mitra baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_perusahaan' => ['required', 'string', 'max:255', 'unique:perusahaan,nama_perusahaan'],
            'alamat' => ['nullable', 'string'],
            'no_telp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bidang_usaha' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $mitra = Mitra::create($data);

        return response()->json([
            'message' => 'Mitra berhasil dibuat',
            'data' => $mitra->load(['supervisor', 'magang'])
        ], 201);
    }

    /**
     * Update data mitra.
     */
    public function update(Request $request, $id)
    {
        $mitra = Mitra::findOrFail($id);

        $rules = [
            'nama_perusahaan' => [
                'required', 'string', 'max:255',
                Rule::unique('perusahaan', 'nama_perusahaan')->ignore($mitra->perusahaan_id, 'perusahaan_id')
            ],
            'alamat' => ['nullable', 'string'],
            'no_telp' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'bidang_usaha' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $mitra->update($data);

        return response()->json([
            'message' => 'Mitra berhasil diperbarui',
            'data' => $mitra->fresh()->load(['supervisor', 'magang'])
        ], 200);
    }

    /**
     * Hapus mitra.
     */
    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);

        // Jika ingin mencegah penghapusan ketika ada relasi, cek di sini:
        // if ($mitra->supervisor()->exists() || $mitra->magang()->exists()) {
        //     return response()->json(['message' => 'Tidak dapat dihapus: masih memiliki relasi'], 400);
        // }

        $mitra->delete();

        return response()->json([
            'message' => 'Mitra berhasil dihapus'
        ], 200);
    }
}
