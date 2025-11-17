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
        'mitra_id',
        'supervisor_id',
        'dosbing_id',
        'tahun_ajaran',
        'semester_magang',
        'jumlah_magang_ke',
        'role_magang',
        'jobdesk',
        'tanggal_mulai',
        'tanggal_selesai',
        'periode_bulan',
        'status_magang',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // ✅ Tambahkan ini
    protected static function booted()
    {
        static::saving(function ($magang) {
            if (!in_array($magang->semester_magang, [6, 7])) {
                throw new \InvalidArgumentException('Semester magang hanya boleh 6 atau 7.');
            }
        });
    }

    // 🔗 Relasi
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'mahasiswa_id');
    }

    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'supervisor_id');
    }

    public function dosenPembimbing()
    {
        return $this->belongsTo(Dosbing::class, 'dosbing_id', 'dosbing_id');
    }

    public function dokumen()
    {
        return $this->hasMany(DokumenMagang::class, 'magang_id', 'magang_id');
    }

}
