<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supervisor extends Model
{
    use HasFactory;

    protected $table = 'supervisor';
    protected $primaryKey = 'supervisor_id';

    protected $fillable = [
        'user_id',
        'mitra_id',
        'nama_supervisor',
        'jabatan',
        'email',
        'no_hp',
        
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // 🔗 Relasi
    public function mitra()
    {
        return $this->belongsTo(Mitra::class, 'mitra_id', 'mitra_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
