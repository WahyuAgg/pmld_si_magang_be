<?php

namespace App\Http\Controllers;

use App\Models\TahunAjaran;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class TahunAjaranController extends Controller
{
     /**
     * Tampilkan semua tahun ajaran
     */
    public function index()
    {
        $data = TahunAjaran::orderByDesc('tahun_ajaran_id')->get();
        return response()->json($data, 200);
    }

    /**
     * Tampilkan detail tahun ajaran
     */
    public function show($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        return response()->json($tahunAjaran, 200);
    }

    /**
     * Tambah tahun ajaran
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_tahun_ajaran' => ['required', 'string', 'max:50'],
            'semester' => ['required', 'in:Ganjil,Genap'],
            'is_active' => ['required', 'boolean'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Jika tahun ajaran baru dibuat aktif, matikan yang lain
        if ($request->is_active) {
            TahunAjaran::where('is_active', 1)->update(['is_active' => 0]);
        }

        $tahunAjaran = TahunAjaran::create($request->all());

        return response()->json([
            'message' => 'Tahun ajaran berhasil ditambahkan',
            'data' => $tahunAjaran,
        ], 201);
    }

    /**
     * Update tahun ajaran
     */
    public function update(Request $request, $id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);

        $rules = [
            'nama_tahun_ajaran' => ['nullable', 'string', 'max:50'],
            'semester' => ['nullable', 'in:Ganjil,Genap'],
            'is_active' => ['nullable', 'boolean'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Jika tahun ajaran ini diaktifkan, nonaktifkan yang lain
        if ($request->has('is_active') && $request->is_active) {
            TahunAjaran::where('is_active', 1)->where('tahun_ajaran_id', '!=', $id)->update(['is_active' => 0]);
        }

        $tahunAjaran->update($request->only(['nama_tahun_ajaran', 'semester', 'is_active']));

        return response()->json([
            'message' => 'Tahun ajaran berhasil diperbarui',
            'data' => $tahunAjaran->fresh(),
        ], 200);
    }

    /**
     * Hapus tahun ajaran
     */
    public function destroy($id)
    {
        $tahunAjaran = TahunAjaran::findOrFail($id);
        $tahunAjaran->delete();

        return response()->json([
            'message' => 'Tahun ajaran berhasil dihapus',
        ], 200);
    }

    /**
     * Ambil tahun ajaran aktif
     */
    public function aktif()
    {
        $tahunAjaran = TahunAjaran::where('is_active', 1)->first();
        return response()->json($tahunAjaran, 200);
    }
}
