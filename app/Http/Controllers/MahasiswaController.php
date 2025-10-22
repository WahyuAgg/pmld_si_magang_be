<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;



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

        $perPage = (int) $request->query('per_page', 20);
        $mahasiswa = $query->orderByDesc('mahasiswa_id')->paginate($perPage);

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
            'nim' => ['required', 'string', 'max:50', 'unique:mahasiswa,nim'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'semester' => ['nullable', 'integer'],
            'alamat' => ['nullable', 'string'],
            'foto_profile' => ['nullable', 'image', 'max:2048'], // max 2MB
            'status_aktif' => ['nullable', Rule::in([0, 1])],
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

        // Pecah NIM format: YY/NIU/Fakultas/NIF
        $nimParts = explode("/", $data['nim']);
        if (count($nimParts) !== 4) {
            return response()->json([
                'message' => 'Format NIM tidak valid. Gunakan format: YY/NIU/Fakultas/NIF'
            ], 422);
        }

        $angkatan = "20" . $nimParts[0]; // misal "23" → "2023"
        $niu = $nimParts[1];
        $kodeFakultas = $nimParts[2];
        $nif = $nimParts[3];

        // Buat atau temukan user berdasarkan NIU
        $user = User::firstOrCreate(
            ['username' => $niu],
            [
                'email' => $data['email'],
                'password' => Hash::make($nif), // password = NIF
                'role' => 'mahasiswa'
            ]
        );

        // Tambahkan user_id dan angkatan ke data mahasiswa
        $data['user_id'] = $user->user_id;
        $data['angkatan'] = $angkatan;

        // Buat data mahasiswa
        $mahasiswa = Mahasiswa::create($data);

        return response()->json([
            'message' => 'Mahasiswa berhasil dibuat',
            'data' => $mahasiswa->load(['user', 'magang'])
        ], 201);
    }


    /**
     * Update data mahasiswa.
     */
    public function update(Request $request)
    {

        $user = auth()->user();
        $userId = auth()->user()->user_id;

        $mahasiswa = Mahasiswa::where('user_id', $userId)->firstOrFail();

        $rules = [
            'nim' => ['required', 'string', 'max:50'],
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'no_hp' => ['nullable', 'string', 'max:30'],
            'angkatan' => ['nullable', 'integer'],
            'semester' => ['nullable', 'integer'],
            'alamat' => ['nullable', 'string'],
            'foto_profile' => ['nullable', 'image', 'max:2048'],
            'status_aktif' => ['nullable', Rule::in([0, 1])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $user->update(['email' => $data['email']]);


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



    // CUSTOM

    public function import0(Request $request)
    {
        // Validasi file CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');

        // Buka file
        $handle = fopen($file->getRealPath(), "r");

        // Lewati header (NO;NIM;NAMA)
        $header = fgetcsv($handle, 1000, ";");

        $imported = [];
        while (($row = fgetcsv($handle, 1000, ";")) !== false) {
            [$no, $nim, $nama] = $row;

            // Buat user baru atau skip jika sudah ada
            $user = User::firstOrCreate(
                ['username' => $nim],
                [
                    'email' => $nim . '@example.com', // kalau email wajib unik
                    'password' => Hash::make($nama),
                ]
            );

            $imported[] = $user;
        }

        fclose($handle);

        return response()->json([
            'message' => 'Import berhasil',
            'data' => $imported,
        ]);
    }

    public function import(Request $request)
    {
        // Validasi file CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');

        // Buka file
        $handle = fopen($file->getRealPath(), "r");

        // Lewati header (No;Nama;NIM)
        $header = fgetcsv($handle, 1000, ";");

        $imported = [];
        while (($row = fgetcsv($handle, 1000, ";")) !== false) {
            [$no, $nama, $nim] = $row;

            // Pecah NIM jadi bagian-bagian
            $nimParts = explode("/", $nim);

            // Pastikan format NIM sesuai (4 bagian)
            if (count($nimParts) !== 4) {
                continue; // skip data kalau NIM format tidak sesuai
            }

            $angkatan = "20" . $nimParts[0]; // "23" -> "2023"
            $niu = $nimParts[1];
            $kodeFakultas = $nimParts[2];
            $nif = $nimParts[3];

            // Buat user baru atau skip jika sudah ada
            $user = User::firstOrCreate(
                // where
                ['username' => $niu],
                // data, if not found
                [
                    'email' => $niu . '@example.com', // email dummy unik
                    'password' => Hash::make($nif), // password dari bagian akhir
                    'role' => "mahasiswa"
                ]
            );

            $mahasiswa = Mahasiswa::firstOrCreate(
                ['nim' => $nim],
                [
                    'user_id' => $user->user_id,
                    'nama' => $nama,
                    'angkatan' => $angkatan

                ]

            );

            $imported[] = $user;
        }

        fclose($handle);

        return response()->json([
            'message' => 'Import berhasil',
            'data' => $imported,
        ]);
    }

}
