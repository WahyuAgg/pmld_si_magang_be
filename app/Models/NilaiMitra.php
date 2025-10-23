<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiMitra extends Model
{
    protected $table = 'penilaian_mitra';
    protected $primaryKey = 'penilaian_id';

    protected $fillable = [
        'magang_id',
        'supervisor_id',
        'nilai_teknis',
        'nilai_profesionalisme_etika',
        'nilai_komunikasi_presentasi',
        'nilai_proyek_pengalaman_industri',
        'keterangan',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relasi: PenilaianMitra belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }

}
