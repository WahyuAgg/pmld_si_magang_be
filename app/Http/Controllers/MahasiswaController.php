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

        $user = $request->user(); // ambil user dari token
        $mahasiswaId = optional($user->mahasiswa)->mahasiswa_id; // gunakan optional agar aman

        // Filter otomatis jika role adalah mahasiswa
        if ($user->role === 'mahasiswa'&& $mahasiswaId) {
            // asumsikan user.id terhubung ke mahasiswa.id atau ke field user_id di tabel mahasiswa
            $query->where('mahasiswa_id', $mahasiswaId);
        }

        // 🔍 Pencarian global (nama atau NIM)
        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        // 🎓 Filter angkatan (tahun masuk)
        if ($angkatan = $request->query('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        // 🧭 Filter semester magang // draft
        if ($semester_magang = $request->get('semester_magang')) {
            $query->whereHas('magang', function ($q) use ($semester_magang) {
                $q->where('semester_magang', $semester_magang);
            });
        }

        // 👤 Filter berdasarkan user_id
        if ($userId = $request->query('user_id')) {
            $query->where('user_id', $userId);
        }

        // 📅 Filter range angkatan (contoh: 2020-2023)
        // if ($range = $request->query('angkatan_range')) {
        //     [$from, $to] = explode('-', $range);
        //     $query->whereBetween('angkatan', [(int) $from, (int) $to]);
        // }

        // 📈 Sorting dinamis
        $sortBy = $request->query('sort_by', 'mahasiswa_id');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 📄 Pagination
        $perPage = (int) $request->query('per_page', 10);
        $mahasiswa = $query->paginate($perPage);
        // $mahasiswa = $query->get();


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
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

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
                'email' => null,
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
            'angkatan' => ['nullable', 'integer'],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

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

        $mahasiswa->delete();

        return response()->json([
            'message' => 'Mahasiswa berhasil dihapus'
        ], 200);
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
                    'email' => null, 
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
