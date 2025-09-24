<?php

namespace App\Http\Controllers;

use App\Models\Supervisor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class SupervisorController extends Controller
{
       /**
     * Tampilkan daftar supervisor
     */
    public function index(Request $request)
    {
        $query = Supervisor::with(['perusahaan', 'user']);

        // Filter berdasarkan perusahaan_id
        if ($perusahaanId = $request->query('perusahaan_id')) {
            $query->where('perusahaan_id', $perusahaanId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $data = $query->latest('supervisor_id')->paginate($perPage);

        return response()->json($data, 200);
    }

    /**
     * Tampilkan detail supervisor
     */
    public function show($id)
    {
        $supervisor = Supervisor::with(['perusahaan', 'user'])->findOrFail($id);
        return response()->json($supervisor, 200);
    }

    /**
     * Tambah supervisor baru
     */
    public function store(Request $request)
    {
        $rules = [
            'user_id' => ['nullable', 'exists:users,user_id'],
            'perusahaan_id' => ['required', 'exists:perusahaan,perusahaan_id'],
            'nama_supervisor' => ['required', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'unique:supervisor_perusahaan,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $supervisor = Supervisor::create($request->all());

        return response()->json([
            'message' => 'Supervisor berhasil ditambahkan',
            'data' => $supervisor->load(['perusahaan', 'user']),
        ], 201);
    }

    /**
     * Update supervisor
     */
    public function update(Request $request, $id)
    {
        $supervisor = Supervisor::findOrFail($id);

        $rules = [
            'perusahaan_id' => ['nullable', 'exists:perusahaan,perusahaan_id'],
            'nama_supervisor' => ['nullable', 'string', 'max:100'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'unique:supervisor_perusahaan,email,' . $supervisor->supervisor_id . ',supervisor_id'],
            'no_hp' => ['nullable', 'string', 'max:20'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $supervisor->update($request->only([
            'perusahaan_id',
            'nama_supervisor',
            'jabatan',
            'email',
            'no_hp',
        ]));

        return response()->json([
            'message' => 'Supervisor berhasil diperbarui',
            'data' => $supervisor->fresh()->load(['perusahaan', 'user']),
        ], 200);
    }

    /**
     * Hapus supervisor
     */
    public function destroy($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $supervisor->delete();

        return response()->json([
            'message' => 'Supervisor berhasil dihapus',
        ], 200);
    }
}
