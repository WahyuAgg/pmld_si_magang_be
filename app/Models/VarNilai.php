<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VarNilai extends Model
{
    use HasFactory;

    protected $table = 'variabel_penilaian';
    protected $primaryKey = 'variabel_id';
    public $timestamps = false;

    protected $fillable = [
        'nama_variabel',
        'deskripsi',
        'bobot',
        'is_active',
    ];
}
