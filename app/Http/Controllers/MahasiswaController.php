<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
   /**
     * Tampilkan daftar mahasiswa (dengan pagination dan pencarian sederhana).
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::with(['user', 'magang']);

        // Pencarian: nama atau nim
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // Filter status aktif (opsional)
        if ($request->query('status_aktif') !== null) {
    $query->where('status_aktif', $request->query('status_aktif'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $mahasiswa = $query->orderBy('nama')->paginate($perPage);

        return response()->json($mahasiswa, 200);
    }

    /**
     * Tampilkan detail mahasiswa berdasarkan id (mahasiswa_id).
     */
    public function show($id)
    {
        $mahasiswa = Mahasiswa::with(['user', 'magang'])->findOrFail($id);

        return response()->json($mahasiswa, 200);
    }

    /**
     * Simpan mahasiswa baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => ['nullable', 'integer', 'exists:users,user_id'],
            'nim' => ['required', 'string', 'max:50', 'unique:mahasiswa,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'angkatan' => ['nullable', 'integer'],
            'semester' => ['nullable', 'integer'],
            'alamat' => ['nullable', 'string'],
            'foto_profile' => ['nullable', 'image', 'max:2048'], // max 2MB
            'status_aktif' => ['nullable', Rule::in([0,1])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Tangani upload foto_profile jika ada
        if ($request->hasFile('foto_profile')) {
            $path = $request->file('foto_profile')->store('foto_profile', 'public');
            $data['foto_profile'] = $path;
        }

        $mahasiswa = Mahasiswa::create($data);

        return response()->json([
            'message' => 'Mahasiswa berhasil dibuat',
            'data' => $mahasiswa->load(['user', 'magang'])
        ], 201);
    }

    /**
     * Update data mahasiswa.
     */
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        $rules = [
            'user_id' => ['nullable', 'integer', 'exists:users,user_id'],
            'nim' => ['required', 'string', 'max:50', Rule::unique('mahasiswa','nim')->ignore($mahasiswa->mahasiswa_id, 'mahasiswa_id')],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'angkatan' => ['nullable', 'integer'],
            'semester' => ['nullable', 'integer'],
            'alamat' => ['nullable', 'string'],
            'foto_profile' => ['nullable', 'image', 'max:2048'],
            'status_aktif' => ['nullable', Rule::in([0,1])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        // Tangani pergantian foto_profile
        if ($request->hasFile('foto_profile')) {
            // Hapus file lama jika ada
            if (!empty($mahasiswa->foto_profile) && Storage::disk('public')->exists($mahasiswa->foto_profile)) {
                Storage::disk('public')->delete($mahasiswa->foto_profile);
            }
            $path = $request->file('foto_profile')->store('foto_profile', 'public');
            $data['foto_profile'] = $path;
        }

        $mahasiswa->update($data);

        return response()->json([
            'message' => 'Mahasiswa berhasil diperbarui',
            'data' => $mahasiswa->fresh()->load(['user', 'magang'])
        ], 200);
    }

    /**
     * Hapus mahasiswa.
     */
    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);

        // Hapus file foto_profile jika ada
        if (!empty($mahasiswa->foto_profile) && Storage::disk('public')->exists($mahasiswa->foto_profile)) {
            Storage::disk('public')->delete($mahasiswa->foto_profile);
        }

        $mahasiswa->delete();

        return response()->json([
            'message' => 'Mahasiswa berhasil dihapus'
        ], 200);
    }
}
