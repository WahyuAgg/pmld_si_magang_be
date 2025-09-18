<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalPresentasi extends Model
{
    protected $table = 'jadwal_presentasi';
    protected $primaryKey = 'jadwal_id';

    protected $fillable = [
        'magang_id',
        'tanggal_presentasi',
        'waktu_mulai',
        'waktu_selesai',
        'tempat',
        'ruangan',
        'keterangan',
        'status',
        'created_by',
    ];

    // Relasi: JadwalPresentasi belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }
}
