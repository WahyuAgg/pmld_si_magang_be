<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dosbing extends Model
{
    use HasFactory;

    protected $table = 'dosen_pembimbing';
    protected $primaryKey = 'dosbing_id';

    protected $fillable = [
        'nip',
        'nama',
        'email',
        'no_hp',
        'jabatan',
    ];

    // 🔗 Relasi
    public function magang()
    {
        return $this->hasMany(Magang::class, 'dosbing_id', 'dosbing_id');
    }
}
