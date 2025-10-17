<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';
    protected $primaryKey = 'perusahaan_id';

    protected $fillable = [
        'nama_mitra',
        'alamat',
        'no_telp',
        'email',
        'website',
        'bidang_usaha',
        'deskripsi',
    ];

    // 🔗 Relasi
    public function supervisor()
    {
        return $this->hasMany(Supervisor::class, 'mitra_id', 'mitra_id');
    }

    public function magang()
    {
        return $this->hasMany(Magang::class, 'mitra_id', 'mitra_id');
    }
}
