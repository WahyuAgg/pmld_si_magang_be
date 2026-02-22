<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoMagang extends Model
{
    protected $table = 'foto_kegiatan';
    protected $primaryKey = 'foto_id';

    protected $fillable = [
        'logbook_id',
        'nama_file',
        'file_path',
        'keterangan',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // Relasi: FotoKegiatan belongsTo LogbookMagang
    public function logbook()
    {
        return $this->belongsTo(Logbook::class, 'logbook_id', 'logbook_id');
    }
}
