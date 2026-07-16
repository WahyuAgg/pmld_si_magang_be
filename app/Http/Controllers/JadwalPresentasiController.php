<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JadwalPresentasi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class JadwalPresentasiController extends Controller
{
        /**
     * Tampilkan daftar jadwal presentasi (dengan filter & pagination).
     */
    public function index(Request $request)
    {
        // Gunakan query builder, bukan get()
        $query = JadwalPresentasi::query();

        // Filter berdasarkan status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Filter berdasarkan tanggal
        if ($tanggal = $request->query('tanggal_presentasi')) {
            $query->whereDate('tanggal_presentasi', $tanggal);
        }

        // Pagination dan urutan
        $perPage = (int) $request->query('per_page', 15);
        $jadwal = $query->orderBy('tanggal_presentasi')->paginate($perPage);

        return response()->json($jadwal, 200);
    }


    /**
     * Tampilkan detail jadwal presentasi.
     */
    public function show($id)
    {
        $jadwal = JadwalPresentasi::findOrFail($id);
        return response()->json($jadwal, 200);
    }

    /**
     * Simpan jadwal presentasi baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'tanggal_presentasi' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'ruangan' => ['required', 'string', 'max:100'],
        ];

        $messages = [
            'waktu_selesai.after' => 'Waktu mulai tidak dapat melebihi waktu akhir'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Validasi gagal',
                'message' => collect($validator->errors()->all())->first(),
            ], 422);
        }

        try {
            $jadwal = JadwalPresentasi::create($validator->validated());
    
            return response()->json([
                'message' => 'Jadwal presentasi berhasil dibuat',
                'data' => $jadwal,
            ], 201);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update jadwal presentasi.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'tanggal_presentasi' => ['sometimes', 'required', 'date'],
            'waktu_mulai' => ['sometimes', 'required', 'date_format:H:i'],
            'waktu_selesai' => ['sometimes', 'required', 'date_format:H:i', 'after:waktu_mulai'],
            'ruangan' => ['required', 'string', 'max:100'],
        ];

        $messages = [
            'waktu_selesai.after' => 'Waktu mulai tidak dapat melebihi waktu akhir'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Validasi gagal',
                'message' => collect($validator->errors()->all())->first(),
            ], 422);
        }

        try {
            $jadwal = JadwalPresentasi::findOrFail($id);
            $jadwal->update($validator->validated());
    
            return response()->json([
                'message' => 'Jadwal presentasi berhasil diperbarui',
                'data' => $jadwal->fresh(),
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus jadwal presentasi.
     */
    public function destroy($id)
    {
        $jadwal = JadwalPresentasi::findOrFail($id);
        $jadwal->delete();

        return response()->json([
            'message' => 'Jadwal presentasi berhasil dihapus',
        ], 200);
    }
}

