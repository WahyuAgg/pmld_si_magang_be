<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    protected $table = 'logbook_magang';
    protected $primaryKey = 'logbook_id';

    protected $fillable = [
        'magang_id',
        'tanggal_kegiatan',
        'kegiatan',
        'deskripsi_kegiatan',
    ];

    // Relasi: LogbookMagang belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }

    // Relasi: LogbookMagang hasMany FotoKegiatan
    public function fotoKegiatan()
    {
        return $this->hasMany(FotoMagang::class, 'logbook_id', 'logbook_id');
    }
}
