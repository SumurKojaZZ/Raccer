<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesertaJadwal extends Model
{
    use HasFactory;

    protected $table = 'peserta_jadwal';
    protected $primaryKey = 'id_peserta_jadwal';

    protected $fillable = [
        'id_jadwal',
        'id_siswa',
        'id_paket_siswa',
        'status_kehadiran',
        'catatan',
    ];

    protected $casts = [
        'id_jadwal' => 'integer',
        'id_siswa' => 'integer',
        'id_paket_siswa' => 'integer',
    ];

    /**
     * Relasi ke Jadwal Kelas
     */
    public function jadwal(): BelongsTo
    {
        return $this->belongsTo(Jadwal::class, 'id_jadwal', 'id');
    }

    /**
     * Relasi ke Data Siswa
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    /**
     * Relasi ke Paket Siswa Aktif
     */
    public function paketSiswa(): BelongsTo
    {
        return $this->belongsTo(PaketSiswa::class, 'id_paket_siswa', 'id_paket_siswa');
    }
}
