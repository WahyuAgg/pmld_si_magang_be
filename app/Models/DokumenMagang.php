<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DokumenMagang extends Model
{
    protected $table = 'dokumen_magang';
    protected $primaryKey = 'dokumen_id';
    public $timestamps = false; // pakai uploaded_at & updated_at

    protected $fillable = [
        'magang_id',
        'jenis_dokumen', //['doc_surat_penerimaan', 'doc_pra_krs', 'doc_laporan_magang', 'doc_penilaian_mitra']
        'nama_file',
        'path_file',
        'ukuran_file',
        'status_dokumen',
        'keterangan',
        'uploaded_at',
        'updated_at',
    ];
    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    // Relasi: DokumenMagang belongsTo Magang
    public function magang()
    {
        return $this->belongsTo(Magang::class, 'magang_id', 'magang_id');
    }
}
