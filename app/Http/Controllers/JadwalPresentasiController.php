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
        $query = JadwalPresentasi::with('magang');

        // Filter berdasarkan magang_id
        if ($magangId = $request->query('magang_id')) {
            $query->where('magang_id', $magangId);
        }

        // Filter berdasarkan status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Filter berdasarkan tanggal
        if ($tanggal = $request->query('tanggal_presentasi')) {
            $query->whereDate('tanggal_presentasi', $tanggal);
        }

        $perPage = (int) $request->query('per_page', 15);
        $jadwal = $query->orderBy('tanggal_presentasi')->paginate($perPage);

        return response()->json($jadwal, 200);
    }

    /**
     * Tampilkan detail jadwal presentasi.
     */
    public function show($id)
    {
        $jadwal = JadwalPresentasi::with('magang')->findOrFail($id);
        return response()->json($jadwal, 200);
    }

    /**
     * Simpan jadwal presentasi baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'magang_id' => ['required', 'exists:magang,magang_id'],
            'tanggal_presentasi' => ['required', 'date'],
            'waktu_mulai' => ['required', 'date_format:H:i'],
            'waktu_selesai' => ['required', 'date_format:H:i', 'after:waktu_mulai'],
            'tempat' => ['required', 'string', 'max:150'],
            'ruangan' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:terjadwal,selesai,dibatalkan'],
            'created_by' => ['required', 'string', 'max:100'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jadwal = JadwalPresentasi::create($validator->validated());

        return response()->json([
            'message' => 'Jadwal presentasi berhasil dibuat',
            'data' => $jadwal->load('magang'),
        ], 201);
    }

    /**
     * Update jadwal presentasi.
     */
    public function update(Request $request, $id)
    {
        $jadwal = JadwalPresentasi::findOrFail($id);

        $rules = [
            'tanggal_presentasi' => ['sometimes', 'required', 'date'],
            'waktu_mulai' => ['sometimes', 'required', 'date_format:H:i'],
            'waktu_selesai' => ['sometimes', 'required', 'date_format:H:i', 'after:waktu_mulai'],
            'tempat' => ['sometimes', 'required', 'string', 'max:150'],
            'ruangan' => ['nullable', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:terjadwal,selesai,dibatalkan'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $jadwal->update($validator->validated());

        return response()->json([
            'message' => 'Jadwal presentasi berhasil diperbarui',
            'data' => $jadwal->fresh()->load('magang'),
        ], 200);
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

