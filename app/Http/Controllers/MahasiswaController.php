<?php

namespace App\Http\Controllers;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\DB;
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

        if ($user->role === 'mahasiswa' && $mahasiswaId) {
            // asumsikan user.id terhubung ke mahasiswa.id atau ke field user_id di tabel mahasiswa
            $query->where('mahasiswa_id', $mahasiswaId);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nim', 'like', "%{$search}%");
            });
        }

        if ($angkatan = $request->query('angkatan')) {
            $query->where('angkatan', $angkatan);
        }

        if ($semester_magang = $request->get('semester_magang')) {
            $query->whereHas('magang', function ($q) use ($semester_magang) {
                $q->where('semester_magang', $semester_magang);
            });
        }

        $sortBy = $request->query('sort_by', 'mahasiswa_id');
        $sortOrder = $request->query('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = (int) $request->query('per_page', 10);
        $mahasiswa = $query->paginate($perPage);

        return response()->json($mahasiswa, 200);
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $mahasiswa_id = optional($user->mahasiswa)->mahasiswa_id;
        $mahasiswa = Mahasiswa::findOrFail($mahasiswa_id);

        return response()->json($mahasiswa, 200);
    }


    /**
     * Tampilkan detail mahasiswa berdasarkan id (mahasiswa_id).
     */
    // public function show($id)
    // {
    //     $mahasiswa = Mahasiswa::with(['user', 'magang'])->findOrFail($id);

    //     return response()->json($mahasiswa, 200);
    // }

    /**
     * Simpan mahasiswa baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'nim' => ['required', 'string', 'max:50', 'unique:mahasiswa,nim'],
            'nama' => ['required', 'string', 'max:255'],
        ];

        $messages = [
            'nim.unique' => 'Mahasiswa dengan NIM tersebut telah terdaftar'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json([
                'title' => 'Validasi Gagal',
                'message' => collect($validator->errors()->all())->first()
            ], 422);
        }

        try {
            $data = $validator->validated();

            $nimParts = explode("/", $data['nim']);
            if (count($nimParts) !== 4) {
                return response()->json([
                    'message' => 'Format NIM tidak valid. Gunakan format: YY/NIU/Fakultas/NIF'
                ], 422);
            }

            $angkatan = "20" . $nimParts[0];
            $niu = $nimParts[1];
            $kodeFakultas = $nimParts[2];
            $nif = $nimParts[3];

            $user = User::firstOrCreate(
                ['username' => $niu],
                [
                    'email' => null,
                    'password' => Hash::make($nif),
                    'role' => 'mahasiswa'
                ]
            );

            $data['user_id'] = $user->user_id;
            $data['angkatan'] = $angkatan;

            $mahasiswa = Mahasiswa::create($data);

            return response()->json([
                'message' => 'Mahasiswa berhasil ditambahkan.',
                'data' => $mahasiswa->load(['user', 'magang'])
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Update data mahasiswa.
     */
    public function update(Request $request)
    {
        $rules = [
            'mahasiswa_id' => ['required', 'integer', 'exists:mahasiswa,mahasiswa_id'],
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

        try {
            $mahasiswa = Mahasiswa::findOrFail($data['mahasiswa_id']);
    
            unset($data['mahasiswa_id']);
    
            $mahasiswa->update($data);
    
            return response()->json([
                'message' => 'Mahasiswa berhasil diperbarui',
                'data' => $mahasiswa->fresh()->load(['user', 'magang'])
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus mahasiswa.
     */
    public function destroy($id)
    {
        try {
            $mahasiswa = Mahasiswa::findOrFail($id);
    
            $mahasiswa->delete();
    
            return response()->json([
                'message' => 'Mahasiswa berhasil dihapus'
            ], 200);
            
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }


    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), "r");
        $header = fgetcsv($handle, 1000, ";");

        $errors = [];
        $imported = [];
        $lineNumber = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle, 1000, ";")) !== false) {
                $lineNumber++;

                if (count($row) < 3) {
                    $errors[] = "Baris ke-{$lineNumber}: Data tidak lengkap";
                    continue;
                }

                [$no, $nama, $nim] = $row;

                if (empty($nim)) {
                    $errors[] = "Baris ke-{$lineNumber}: NIM tidak boleh kosong";
                    continue;
                }

                $e = Mahasiswa::where(function ($query) use ($nim) {
                    $query->where('nim', '=', $nim);
                });

                if ($e->exists()) {
                    $errors[] = "Baris ke-{$lineNumber}: NIM sudah terdaftar";
                    
                    continue;
                }

                if (empty($nama)) {
                    $errors[] = "Baris ke-{$lineNumber}: Nama tidak boleh kosong";
                    continue;
                }

                $nimParts = explode("/", $nim);

                if (count($nimParts) !== 4) {
                    $errors[] = "Baris ke-{$lineNumber}: Format NIM tidak sesuai (harus XX/XXXXX/XX/XXXXX), ditemukan: {$nim}";
                    continue;
                }

                [$tahun, $niu, $kodeFakultas, $nif] = $nimParts;

                if (!ctype_digit($tahun) || strlen($tahun) !== 2) {
                    $errors[] = "Baris ke-{$lineNumber}: Tahun angkatan harus 2 digit angka, ditemukan: {$tahun}";
                    continue;
                }

                if (!ctype_digit($niu)) {
                    $errors[] = "Baris ke-{$lineNumber}: NIU harus berupa angka, ditemukan: {$niu}";
                    continue;
                }

                if (strlen($kodeFakultas) !== 2 || !ctype_alpha($kodeFakultas)) {
                    $errors[] = "Baris ke-{$lineNumber}: Kode fakultas harus 2 huruf, ditemukan: {$kodeFakultas}";
                    continue;
                }

                if (!ctype_digit($nif)) {
                    $errors[] = "Baris ke-{$lineNumber}: NIF harus berupa angka, ditemukan: {$nif}";
                    continue;
                }

                $angkatan = "20" . $tahun;

                if ($angkatan < 2000 || $angkatan > 2030) {
                    $errors[] = "Baris ke-{$lineNumber}: Tahun angkatan tidak valid: {$angkatan}";
                    continue;
                }

                $user = User::firstOrCreate(
                    ['username' => $niu],
                    [
                        'email' => null,
                        'password' => Hash::make($nif),
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

                $imported[] = [
                    'nim' => $nim,
                    'nama' => $nama,
                    'angkatan' => $angkatan
                ];
            }

            fclose($handle);

            if (!empty($errors)) {
                DB::rollBack();
                return response()->json([
                    'message' => 'Import gagal',
                    'errors' => $errors,
                    'imported_count' => 0,
                    'failed_count' => count($errors)
                ], 422);
            }

            DB::commit();

            return response()->json([
                'message' => 'Import berhasil',
                'data' => $imported,
                'imported_count' => count($imported),
                'failed_count' => 0
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);

            return response()->json([
                'message' => 'Terjadi kesalahan saat import',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
