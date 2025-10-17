<?php

namespace App\Http\Controllers;
use App\Models\Magang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class MagangController extends Controller
{
    /**
     * Tampilkan daftar magang (dengan pagination, filter, dan pencarian).
     */
    public function index(Request $request)
    {
        $query = Magang::with(['mahasiswa', 'mitra', 'dosenPembimbing']);

        // 🔎 Filter berdasarkan mahasiswa
        if ($mahasiswaId = $request->query('mahasiswa_id')) {
            $query->where('mahasiswa_id', $mahasiswaId);
        }

        // 🔎 Filter berdasarkan mitra
        if ($mitraId = $request->query('mitra_id')) {
            $query->where('mitra_id', $mitraId);
        }

        // 🔎 Filter berdasarkan status
        if ($status = $request->query('status_magang')) {
            $query->where('status_magang', $status);
        }

        // 🔎 Pencarian jobdesk/role
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('jobdesk', 'like', "%{$search}%")
                  ->orWhere('role_magang', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->query('per_page', 15);
        $magang = $query->orderByDesc('tanggal_mulai')->paginate($perPage);

        return response()->json($magang, 200);
    }

    /**
     * Tampilkan detail magang berdasarkan id (magang_id).
     */
    public function show($id)
    {
        $magang = Magang::with(['mahasiswa', 'mitra', 'dosenPembimbing'])->findOrFail($id);

        return response()->json($magang, 200);
    }

    /**
     * Simpan magang baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'mahasiswa_id' => ['required', 'exists:mahasiswa,mahasiswa_id'],
            'mitra_id' => ['required', 'exists:mitra,mitra_id'],
            'dosbing_id' => ['nullable', 'exists:dosbing,dosbing_id'],
            'tahun_ajaran' => ['required'],
            'semester_magang' => ['required', 'string', 'max:20'],
            'jumlah_magang_ke' => ['nullable', 'integer', 'min:1'],
            'role_magang' => ['nullable', 'string', 'max:100'],
            'jobdesk' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'periode_bulan' => ['nullable', 'integer', 'min:1'],
            'status_magang' => ['nullable', Rule::in(['pending','berjalan','selesai','batal'])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $magang = Magang::create($data);

        return response()->json([
            'message' => 'Data magang berhasil dibuat',
            'data' => $magang->load(['mahasiswa', 'mitra', 'dosenPembimbing'])
        ], 201);
    }

    /**
     * Update data magang.
     */
    public function update(Request $request, $id)
    {
        $magang = Magang::findOrFail($id);

        $rules = [
            'mahasiswa_id' => ['sometimes', 'exists:mahasiswa,mahasiswa_id'],
            'mitra_id' => ['sometimes', 'exists:mitra,mitra_id'],
            'dosbing_id' => ['nullable', 'exists:dosen_pembimbing,dosbing_id'],
            'tahun_ajaran' => ['sometimes'],
            'semester_magang' => ['sometimes', 'integer', Rule::in([6, 7])],
            'jumlah_magang_ke' => ['nullable', 'integer', 'min:1'],
            'role_magang' => ['nullable', 'string', 'max:100'],
            'jobdesk' => ['nullable', 'string'],
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_selesai' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'periode_bulan' => ['nullable', 'integer', 'min:1'],
            'status_magang' => ['nullable', Rule::in(['draft', 'berlangsung', 'selesai', 'ditolak'])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $magang->update($data);

        return response()->json([
            'message' => 'Data magang berhasil diperbarui',
            'data' => $magang->fresh()->load(['mahasiswa', 'mitra', 'dosenPembimbing'])
        ], 200);
    }

    /**
     * Hapus data magang.
     */
    public function destroy($id)
    {
        $magang = Magang::findOrFail($id);

        $magang->delete();

        return response()->json([
            'message' => 'Data magang berhasil dihapus'
        ], 200);
    }
}
