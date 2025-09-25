<?php

namespace App\Http\Controllers;

use App\Models\DokumenNilaiMitra;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class DokumenNilaiMitraController extends Controller
{
        /**
     * Tampilkan daftar dokumen penilaian mitra
     */
    public function index(Request $request)
    {
        $query = DokumenNilaiMitra::with(['magang', 'supervisor']);

        // Filter berdasarkan magang
        if ($magangId = $request->query('magang_id')) {
            $query->where('magang_id', $magangId);
        }

        // Filter berdasarkan supervisor
        if ($supervisorId = $request->query('supervisor_id')) {
            $query->where('supervisor_id', $supervisorId);
        }

        // Filter berdasarkan jenis dokumen
        if ($jenis = $request->query('jenis_dokumen')) {
            $query->where('jenis_dokumen', $jenis);
        }

        $perPage = (int) $request->query('per_page', 15);
        $dokumen = $query->latest('uploaded_at')->paginate($perPage);

        return response()->json($dokumen, 200);
    }

    /**
     * Detail dokumen penilaian mitra
     */
    public function show($id)
    {
        $dokumen = DokumenNilaiMitra::with(['magang', 'supervisor'])->findOrFail($id);
        return response()->json($dokumen, 200);
    }

    /**
     * Simpan dokumen baru
     */
    public function store(Request $request)
    {
        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'supervisor_id' => ['required', 'exists:supervisor,supervisor_id'],
            'jenis_dokumen' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'], // max 2MB
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Simpan file ke storage
        $path = $request->file('file')->store('dokumen_penilaian', 'public');

        $dokumen = DokumenNilaiMitra::create([
            'magang_id' => $request->magang_id,
            'supervisor_id' => $request->supervisor_id,
            'nama_file' => $request->file('file')->getClientOriginalName(),
            'path_file' => $path,
            'jenis_dokumen' => $request->jenis_dokumen,
            'keterangan' => $request->keterangan,
            'uploaded_at' => now(),
        ]);

        return response()->json([
            'message' => 'Dokumen berhasil diupload',
            'data' => $dokumen->load(['magang', 'supervisor']),
        ], 201);
    }

    /**
     * Update dokumen
     */
    public function update(Request $request, $id)
    {
        $dokumen = DokumenNilaiMitra::findOrFail($id);

        $rules = [
            'jenis_dokumen' => ['sometimes', 'required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,jpg,jpeg,png', 'max:2048'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Jika ada file baru, hapus file lama lalu simpan yang baru
        if ($request->hasFile('file')) {
            if ($dokumen->path_file && Storage::disk('public')->exists($dokumen->path_file)) {
                Storage::disk('public')->delete($dokumen->path_file);
            }

            $path = $request->file('file')->store('dokumen_penilaian', 'public');

            $dokumen->update([
                'nama_file' => $request->file('file')->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_at' => now(),
            ]);
        }

        $dokumen->update($request->only(['jenis_dokumen', 'keterangan']));

        return response()->json([
            'message' => 'Dokumen berhasil diperbarui',
            'data' => $dokumen->fresh()->load(['magang', 'supervisor']),
        ], 200);
    }

    /**
     * Hapus dokumen
     */
    public function destroy($id)
    {
        $dokumen = DokumenNilaiMitra::findOrFail($id);

        if ($dokumen->path_file && Storage::disk('public')->exists($dokumen->path_file)) {
            Storage::disk('public')->delete($dokumen->path_file);
        }

        $dokumen->delete();

        return response()->json([
            'message' => 'Dokumen berhasil dihapus',
        ], 200);
    }
}
