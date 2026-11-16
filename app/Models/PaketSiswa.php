<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaketSiswa extends Model
{
    use HasFactory;

    protected $table = 'paket_siswa';

    protected $primaryKey = 'id_paket_siswa';

    protected $fillable = [
        'id_siswa',
        'program',
        'jenis_paket',
        'paket_belajar',
        'total_sesi',
        'sesi_terpakai',
        'status_paket',
        'status_pembayaran',
        'metode_pembayaran',
        'catatan',
        'tanggal_aktivasi',
    ];

    protected $casts = [
        'total_sesi' => 'integer',
        'sesi_terpakai' => 'integer',
        'tanggal_aktivasi' => 'datetime',
    ];

    /**
     * Relasi ke model Siswa (pemilik paket).
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
