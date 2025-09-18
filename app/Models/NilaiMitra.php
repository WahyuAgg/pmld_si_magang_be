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
        'nilai',
        'keterangan',
    ];

    // Relasi: PenilaianMitra belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }

    // Relasi: PenilaianMitra belongsTo SupervisorPerusahaan
    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'supervisor_id');
    }


}
