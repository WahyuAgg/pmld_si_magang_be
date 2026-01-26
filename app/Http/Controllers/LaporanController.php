<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Magang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::with('magang')
            ->whereHas('magang', function ($q) {
                $q->where('mahasiswa_id', auth()->id());
            })
            ->get();

        return response()->json($laporan);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'magang_id' => 'required|exists:magangs,id',
            'file'      => 'required|file|mimes:pdf|max:2048',
        ]);

        $magang = Magang::where('magang_id', $request->magang_id)
            ->where('mahasiswa_id', auth()->id())
            ->firstOrFail();

        // 🔒 Guard bisnis
        if ($magang->laporan) {
            return response()->json([
                'message' => 'Laporan magang sudah ada'
            ], 409);
        }

        $file     = $request->file('file');
        $path     = $file->store('laporan-magang', 'public');
        $namaFile = $file->getClientOriginalName();

        $laporan = Laporan::create([
            'magang_id' => $magang->id,
            'file_path' => $path,
            'nama_fifle' => $namaFile,
        ]);

        return response()->json([
            'message' => 'Laporan magang berhasil diunggah',
            'data'    => $laporan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $magangId)
    {
        $laporan = Laporan::whereHas('magang', function ($q) use ($magangId) {
            $q->where('id', $magangId)
              ->where('mahasiswa_id', auth()->id());
        })->firstOrFail();

        return response()->json($laporan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $magangId)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf|max:2048',
        ]);

        $laporan = Laporan::whereHas('magang', function ($q) use ($magangId) {
            $q->where('magang_id', $magangId)
              ->where('mahasiswa_id', auth()->id());
        })->firstOrFail();

        // hapus file lama
        Storage::disk('public')->delete($laporan->file_path);

        $file     = $request->file('file');
        $path     = $file->store('laporan-magang', 'public');
        $namaFile = $file->getClientOriginalName();

        $laporan->update([
            'file_path' => $path,
            'nama_file' => $namaFile
        ]);

        return response()->json([
            'message' => 'Laporan magang berhasil diperbarui',
            'data'    => $laporan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($magangId)
    {
        $laporan = Laporan::whereHas('magang', function ($q) use ($magangId) {
            $q->where('id', $magangId)
              ->where('mahasiswa_id', auth()->id());
        })->firstOrFail();

        Storage::disk('public')->delete($laporan->file_path);
        $laporan->delete();

        return response()->json([
            'message' => 'Laporan magang berhasil dihapus'
        ]);
    }
}
