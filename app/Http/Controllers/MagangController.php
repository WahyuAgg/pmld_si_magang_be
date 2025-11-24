<?php

namespace App\Http\Controllers;
use App\Models\Magang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


use Illuminate\Http\Request;

class MagangController extends Controller
{
    /**
     * Tampilkan daftar magang (dengan pagination, filter, dan pencarian).
     */
    public function index(Request $request)
    {
        $user = $request->user(); // ambil user dari token
        $mahasiswa = $user->mahasiswa; // jika ada relasi di model User

        $query = Magang::with(['mahasiswa', 'mitra', 'dosenPembimbing']);

        // Filter otomatis jika role adalah mahasiswmahasiswaIda
        if ($user->role === 'mahasiswa') {
            // asumsikan user.id terhubung ke mahasiswa.id atau ke field user_id di tabel mahasiswa
            $query->where('mahasiswa_id', $mahasiswa->mahasiswa_id);
        }

        // 🔎 Filter manual berdasarkan query param (admin bisa gunakan ini)
        if ($mahasiswaId = $request->query('mahasiswa_id')) {
            $query->where('mahasiswa_id', $mahasiswaId);
        }

        if ($mitraId = $request->query('mitra_id')) {
            $query->where('mitra_id', $mitraId);
        }

        if ($status = $request->query('jumlah_magang')) {
            $query->where('status_magang', $status);
        }

        if ($status = $request->query('status_magang')) {
            $query->where('status_magang', $status);
        }


        if ($angkatan = $request->query('angkatan')) {
            $query->whereHas('mahasiswa', function ($q) use ($angkatan) {
                $q->where('angkatan', $angkatan);
            });
        }

        if ($semester_magang = $request->query('semester_magang')) {
            $query->where('semester_magang', $semester_magang);
        }

        if ($search = $request->query('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('jobdesk', 'like', "%{$search}%")
                    ->orWhere('role_magang', 'like', "%{$search}%");
            });
        }

        // $perPage = (int) $request->query('per_page', 15);
        // $magang = $query->orderByDesc('tanggal_mulai')->paginate($perPage);
        $magang = $query->orderByDesc('tanggal_mulai')->get();

        // return response()->json([$user, $mahasiswa], 200);



        return response()->json($magang, 200);
    }


    /**
     * Tampilkan detail magang berdasarkan id (magang_id).
     */
    public function show($id)
    {
        $magang = Magang::with(['mahasiswa', 'mitra', 'dosenPembimbing'])->findOrFail($id);

        return response()->json($magang, 200);
    }

    /**
     * Simpan magang baru.
     */
    public function store(Request $request)
    {
        $rules = [
            'mahasiswa_id' => ['required', 'exists:mahasiswa,mahasiswa_id'],
            'mitra_id' => ['required', 'exists:mitra,mitra_id'],
            'dosbing_id' => ['nullable'],
            'supervisor_id' => ['nullable', 'exists:supervisor,supervisor_id'],
            'tahun_ajaran' => ['required'],
            'semester_magang' => ['sometimes', 'integer', Rule::in([6, 7])],
            'jumlah_magang_ke' => ['nullable', 'integer', 'min:1'],
            'role_magang' => ['nullable', 'string', 'max:100'],
            'jobdesk' => ['nullable', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'periode_bulan' => ['nullable', 'integer', 'min:1'],
            'status_magang' => ['nullable', Rule::in(['draft', 'berlangsung', 'selesai', 'ditolak'])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $magang = Magang::create($data);

        return response()->json([
            'message' => 'Data magang berhasil dibuat',
            'data' => $magang->load(['mahasiswa', 'mitra', 'dosenPembimbing'])
        ], 201);
    }

    /**
     * Update data magang.
     */
    public function update(Request $request, $id)
    {
        $magang = Magang::findOrFail($id);

        $rules = [
            'mahasiswa_id' => ['sometimes', 'exists:mahasiswa,mahasiswa_id'],
            'mitra_id' => ['sometimes', 'exists:mitra,mitra_id'],
            'dosbing_id' => ['nullable', 'exists:dosen_pembimbing,dosbing_id'],
            'tahun_ajaran' => ['sometimes'],
            'semester_magang' => ['sometimes', 'integer', Rule::in([6, 7])],
            'jumlah_magang_ke' => ['nullable', 'integer', 'min:1'],
            'role_magang' => ['nullable', 'string', 'max:100'],
            'jobdesk' => ['nullable', 'string'],
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_selesai' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
            'periode_bulan' => ['nullable', 'integer', 'min:1'],
            'status_magang' => ['nullable', Rule::in(['draft', 'berlangsung', 'selesai', 'ditolak'])],
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $validator->validated();

        $magang->update($data);

        return response()->json([
            'message' => 'Data magang berhasil diperbarui',
            'data' => $magang->fresh()->load(['mahasiswa', 'mitra', 'dosenPembimbing'])
        ], 200);
    }

    /**
     * Hapus data magang.
     */
    public function destroy($id)
    {
        $magang = Magang::findOrFail($id);

        $magang->delete();

        return response()->json([
            'message' => 'Data magang berhasil dihapus'
        ], 200);
    }

    /**
     * Menghitung jumlah mahasiswa yang sedang magang (status: berlangsung)
     * pada periode (tahun ajaran) terakhir.
     */
    public function jumlahMagangAktif()
    {
        // 1. Tentukan tahun ajaran terbaru dari data magang yang ada
        $tahunAjaranTerbaru = Magang::latest('tahun_ajaran')->value('tahun_ajaran');

        if (!$tahunAjaranTerbaru) {
            // Jika tidak ada data magang sama sekali, kembalikan 0
            return response()->json(['jumlah_aktif' => 0], 200);
        }

        // 2. Hitung jumlah magang yang 'berlangsung' pada tahun ajaran tersebut
        $jumlahAktif = Magang::where('tahun_ajaran', $tahunAjaranTerbaru)
                              ->where('status_magang', 'berlangsung')
                              ->count();

        return response()->json(['jumlah_aktif' => $jumlahAktif], 200);
    }
}
