<?php

namespace App\Http\Controllers;
use App\Models\ProgressMagang;
use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;

class ProgressMagangController extends Controller
{
      /**
     * Tampilkan daftar progress magang
     */
    public function index(Request $request)
    {
        $query = ProgressMagang::with('magang');

        // Filter by magang_id
        if ($magangId = $request->query('magang_id')) {
            $query->where('magang_id', $magangId);
        }

        $perPage = (int) $request->query('per_page', 15);
        $data = $query->latest('updated_at')->paginate($perPage);

        return response()->json($data, 200);
    }

    /**
     * Tampilkan detail progress magang
     */
    public function show($id)
    {
        $progress = ProgressMagang::with('magang')->findOrFail($id);
        return response()->json($progress, 200);
    }

    /**
     * Tambah progress magang baru
     */
    public function store(Request $request)
    {
        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'tahap' => ['required', 'string', 'max:100'],
            'status' => ['required', 'string', 'max:50'],
            'persentase' => ['required', 'numeric', 'between:0,100'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $progress = ProgressMagang::create([
            'magang_id' => $request->magang_id,
            'tahap' => $request->tahap,
            'status' => $request->status,
            'persentase' => $request->persentase,
            'updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Progress magang berhasil ditambahkan',
            'data' => $progress->load('magang'),
        ], 201);
    }

    /**
     * Update progress magang
     */
    public function update(Request $request, $id)
    {
        $progress = ProgressMagang::findOrFail($id);

        $rules = [
            'tahap' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'max:50'],
            'persentase' => ['nullable', 'numeric', 'between:0,100'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $progress->update(array_merge(
            $request->only(['tahap', 'status', 'persentase']),
            ['updated_at' => now()]
        ));

        return response()->json([
            'message' => 'Progress magang berhasil diperbarui',
            'data' => $progress->fresh()->load('magang'),
        ], 200);
    }

    /**
     * Hapus progress magang
     */
    public function destroy($id)
    {
        $progress = ProgressMagang::findOrFail($id);
        $progress->delete();

        return response()->json([
            'message' => 'Progress magang berhasil dihapus',
        ], 200);
    }
}
