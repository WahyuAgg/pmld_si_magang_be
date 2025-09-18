<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenNilaiMitra extends Model
{
    protected $table = 'dokumen_penilaian_mitra';
    protected $primaryKey = 'dokumen_penilaian_id';
    public $timestamps = false; // pakai uploaded_at

    protected $fillable = [
        'magang_id',
        'supervisor_id',
        'nama_file',
        'path_file',
        'jenis_dokumen',
        'keterangan',
        'uploaded_at',
    ];

    // Relasi: DokumenPenilaianMitra belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }

    // Relasi: DokumenPenilaianMitra belongsTo SupervisorPerusahaan
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'supervisor_id');
    }
}
