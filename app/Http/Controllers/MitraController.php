<?php

namespace App\Http\Controllers;

use App\Models\Mitra;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Magang;
use App\Models\User;
use Illuminate\Support\Str;

use Illuminate\Http\Request;

class MitraController extends Controller
{
    /**
     * Tampilkan daftar mitra (dengan pagination dan pencarian sederhana).
     */
    public function index(Request $request)
    {
        $query = Mitra::with(['magang']);


        // Pencarian: nama_mitra
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_mitra', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->query('per_page', 10);
        $mitra = $query->orderBy('mitra_id')->paginate($perPage);

        return response()->json($mitra, 200);
    }

    /**
     * Tampilkan detail mitra berdasarkan id (mitra_id).
     */
    public function show(Request $request)
    {
        $user = $request->user();
        $mitra_id = optional($user->mitra)->mitra_id;
        $mitra = Mitra::findOrFail($mitra_id);

        return response()->json($mitra, 200);
    }

    /**
     * Simpan mitra baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'nama_mitra' => ['required', 'string', 'max:255', 'unique:mitra,nama_mitra'],
            'email' => ['required', 'email', 'max:255'],
            'narahubung' => ['nullable', 'string'],
        ];

        $messages = [
            'nama_mitra.unique' => 'Mitra dengan nama tersebut sudah ada',
            'email.email' => 'Format email tidak valid',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Validasi Gagal',
                'message' => collect($validator->errors()->all())->first(),
            ], 422);
        }

        try {
            $data = $validator->validated();

            $username = Str::before($data['email'], '@');

            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'email' => $data['email'],
                    'username' => $username,
                    'password' => $username,
                    'role' => 'mitra',
                ]
            );

            $data['user_id'] = $user->user_id;

            $mitra = Mitra::create($data);

            return response()->json([
                'message' => 'Mitra berhasil dibuat',
                'data' => $mitra,
            ], 201);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Update data mitra.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'nama_mitra' => ['required', 'string', 'max:255', 'unique:mitra,nama_mitra,' . $id . ',mitra_id'],
            'email' => ['required', 'email', 'max:255'],
            'narahubung' => ['nullable', 'string'],
        ];

        $messages = [
            'nama_mitra.unique' => 'Mitra dengan nama tersebut sudah ada',
            'email.email' => 'Format email tidak valid',
        ];

        try {
            $mitra = Mitra::findOrFail($id);
            $validator = Validator::make($request->all(), $rules, $messages);
    
            if ($validator->fails()) {
                return response()->json([
                    'title' => "Validasi Gagal",
                    'message' => collect($validator->errors()->all())->first(),
                ], 422);
            }
    
            $data = $validator->validated();
    
            $mitra->update($data);
    
            return response()->json([
                'message' => 'Mitra berhasil diperbarui',
                'data' => $mitra->fresh()->load(['magang'])
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }

    }

    /**
     * Hapus mitra.
     */
    public function destroy($id)
    {
        $mitra = Mitra::findOrFail($id);

        Magang::where('mitra_id', $id)->update(['mitra_id' => null]);

        $mitra->delete();

        return response()->json([
            'message' => 'Mitra berhasil dihapus'
        ], 200);


    }

    public function getMitraByMagang($id)
    {
        $magang = Magang::where('magang_id', $id)->first();
        $mitraId = $magang->mitra_id;
        $mitra = Mitra::where('mitra_id', $mitraId)->first();
        return response()->json($mitra, 200);


    }

    public function jumlahMitra()
    {
        $jumlah = Mitra::count();
        return response()->json(['jumlah_mitra' => $jumlah], 200);
    }
}
