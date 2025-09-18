<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoMagang extends Model
{
    protected $table = 'foto_kegiatan';
    protected $primaryKey = 'foto_id';
    public $timestamps = false; // pakai uploaded_at, bukan created_at/updated_at

    protected $fillable = [
        'logbook_id',
        'nama_file',
        'path_file',
        'keterangan',
        'uploaded_at',
    ];

    // Relasi: FotoKegiatan belongsTo LogbookMagang
    public function logbook()
    {
        return $this->belongsTo(Logbook::class, 'logbook_id', 'logbook_id');
    }
}
