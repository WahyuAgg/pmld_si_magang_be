<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ta extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';
    protected $primaryKey = 'tahun_ajaran_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_tahun_ajaran',
        'semester',
        'is_active',
    ];

    // 🔗 Relasi
    public function magang()
    {
        return $this->hasMany(Magang::class, 'tahun_ajaran_id', 'tahun_ajaran_id');
    }
}
