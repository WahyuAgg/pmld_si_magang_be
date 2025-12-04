<?php

namespace App\Http\Controllers;

use App\Models\Dosbing;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Magang;
use App\Models\Mitra;

use Illuminate\Http\Request;

class DosbingController extends Controller
{
    /**
     * Tampilkan daftar dosen pembimbing
     */
    public function index(Request $request)
    {
        $query = Dosbing::query();

        // Filter berdasarkan nama
        if ($search= $request->query('q')) {
            $query->where('nama', 'like', "%{$search}%");
        }

        // Filter berdasarkan jabatan
        if ($jabatan = $request->query('jabatan')) {
            $query->where('jabatan', $jabatan);
        }

        $perPage = (int) $request->query('per_page', 15);
        $dosbing = $query->orderBy('dosbing_id')->paginate($perPage);

        return response()->json($dosbing, 200);
    }

    /**
     * Detail dosen pembimbing
     */
    public function show($id)
    {
        $dosbing = Dosbing::findOrFail($id);
        return response()->json($dosbing, 200);
    }

    /**
     * Simpan data dosen pembimbing baru
     */
    public function store(Request $request)
    {
        $rules = [
            'nip' => ['required', 'string', 'max:30', 'unique:dosen_pembimbing,nip'],
            'nama' => ['required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'unique:dosen_pembimbing,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dosbing = Dosbing::create($validator->validated());

        return response()->json([
            'message' => 'Dosen pembimbing berhasil ditambahkan',
            'data' => $dosbing,
        ], 201);
    }

    /**
     * Update data dosen pembimbing
     */
    public function update(Request $request, $id)
    {
        $dosbing = Dosbing::findOrFail($id);

        $rules = [
            'nip' => ['sometimes', 'required', 'string', 'max:30', 'unique:dosen_pembimbing,nip,' . $dosbing->dosbing_id . ',dosbing_id'],
            'nama' => ['sometimes', 'required', 'string', 'max:150'],
            'email' => ['nullable', 'email', 'unique:dosen_pembimbing,email,' . $dosbing->dosbing_id . ',dosbing_id'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'jabatan' => ['nullable', 'string', 'max:100'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $dosbing->update($validator->validated());

        return response()->json([
            'message' => 'Dosen pembimbing berhasil diperbarui',
            'data' => $dosbing->fresh(),
        ], 200);
    }

    /**
     * Hapus dosen pembimbing
     */
    public function destroy($id)
    {
        $dosbing = Dosbing::findOrFail($id);
        $dosbing->delete();

        return response()->json([
            'message' => 'Dosen pembimbing berhasil dihapus',
        ], 200);
    }

    public function getDosbingByMagangId($id){
        $magang = Magang::where('magang_id', $id)->first();
        $dosbingId = $magang->dosbing_id;
        $dosbing = Dosbing::where('dosbing_id', $dosbingId)->first();
        return response()->json($dosbing, 200);

    }

    public function jumlahDosbing()
    {
        $jumlahDosbing = Dosbing::count();
        return response()->json(['jumlah_dosbing' => $jumlahDosbing], 200);
    }
}
