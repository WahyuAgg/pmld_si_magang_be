<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisor_perusahaan';
    protected $primaryKey = 'supervisor_id';

    protected $fillable = [
        'user_id',
        'perusahaan_id',
        'nama_supervisor',
        'jabatan',
        'email',
        'no_hp',
    ];

    // 🔗 Relasi
    public function perusahaan()
    {
        return $this->belongsTo(Mitra::class, 'perusahaan_id', 'perusahaan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
