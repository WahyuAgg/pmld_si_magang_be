<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa';
    protected $primaryKey = 'mahasiswa_id';

    protected $fillable = [
        'user_id',
        'nim',
        'nama',
        'email',
        'no_hp',
        'angkatan',
        'semester',
        'alamat',
        'foto_profile',
        'status_aktif',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    // 🔗 Relasi
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function magang()
    {
        return $this->hasMany(Magang::class, 'mahasiswa_id', 'mahasiswa_id');
    }
}
