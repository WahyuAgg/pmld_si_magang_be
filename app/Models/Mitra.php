<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mitra extends Model
{
    use HasFactory;

    protected $table = 'mitra';
    protected $primaryKey = 'mitra_id';

    protected $fillable = [
        'nama_mitra',
        'user_id',
        'alamat',
        'no_telp',
        'email',
        'website',
        'bidang_usaha',
        'deskripsi',
        'narahubung',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deskripsi',
        'bidang_usaha',
        'website',
        'no_telp',
        'alamat',
    ];

    // 🔗 Relasi
    // public function supervisor()
    // {
    //     return $this->hasMany(Supervisor::class, 'mitra_id', 'mitra_id');
    // }

    public function magang()
    {
        return $this->hasMany(Magang::class, 'mitra_id', 'mitra_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
