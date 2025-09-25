<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Magang extends Model
{
    use HasFactory;

    protected $table = 'magang';
    protected $primaryKey = 'magang_id';

    protected $fillable = [
        'mahasiswa_id',
        'perusahaan_id',
        'dosbing_id',
        'tahun_ajaran_id',
        'semester_magang',
        'jumlah_magang_ke',
        'role_magang',
        'jobdesk',
        'tanggal_mulai',
        'tanggal_selesai',
        'periode_bulan',
        'status_magang',
    ];

    // 🔗 Relasi
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function perusahaan()
    {
        return $this->belongsTo(Mitra::class, 'perusahaan_id', 'perusahaan_id');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosbing::class, 'dosbing_id', 'dosbing_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id', 'tahun_ajaran_id');
    }
}
