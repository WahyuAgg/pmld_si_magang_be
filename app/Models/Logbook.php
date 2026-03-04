<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Logbook extends Model
{
    protected $table = 'logbook';
    protected $primaryKey = 'logbook_id';

    protected $fillable = [
        'magang_id',
        'kegiatan',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static function booted()
    {
        static::deleting(function ($logbook) {
            foreach ($logbook->fotoKegiatan as $foto) {
                Storage::delete($foto->file_path);
            }
        });
    }
    
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }

    public function fotoKegiatan()
    {
        return $this->hasMany(FotoMagang::class, 'logbook_id', 'logbook_id');
    }
}
