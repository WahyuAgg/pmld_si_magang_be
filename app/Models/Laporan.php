<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    protected $fillable = [
        'magang_id',
        'nama_file',
        'file_path',
    ];

    public function magang()
    {
        return $this->belongsTo(Magang::class);
    }
}
