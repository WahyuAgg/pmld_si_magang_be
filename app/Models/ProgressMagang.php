<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressMagang extends Model
{
    protected $table = 'progress_magang';
    protected $primaryKey = 'progress_id';
    public $timestamps = false; // hanya updated_at

    protected $fillable = [
        'magang_id',
        'tahap',
        'status',
        'persentase',
        'updated_at',
    ];

    // Relasi: ProgressMagang belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }
}
