<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_keluarga',
        'id_pengguna',
        'nisn',
        'nama_lengkap',
        'nama_panggilan',
        'jenjang',
        'kelas',
        'tanggal_lahir',
        'status_siswa',
        'dibuat_pada',
        'nama',
        'nama_wali',
        'paket_belajar',
        'total_sesi',
        'sesi_terpakai',
        'status_paket',
    ];

    /**
     * Fallback accessor untuk nama siswa.
     */
    public function getNamaAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return $this->attributes['nama_lengkap'] ?? $this->attributes['nama_panggilan'] ?? '-';
    }

    /**
     * Fallback accessor untuk nama wali dari relasi orang_tua / akun_keluarga.
     */
    public function getNamaWaliAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        $idKeluarga = $this->attributes['id_keluarga'] ?? null;
        if ($idKeluarga) {
            try {
                if (Schema::hasTable('orang_tua')) {
                    $ortu = DB::table('orang_tua')
                        ->where('id_keluarga', $idKeluarga)
                        ->orderBy('jenis_wali', 'asc')
                        ->first();
                    if ($ortu && !empty($ortu->nama_lengkap)) {
                        return 'Wali: ' . $ortu->nama_lengkap;
                    }
                }
                if (Schema::hasTable('akun_keluarga')) {
                    $keluarga = DB::table('akun_keluarga')
                        ->where('id_keluarga', $idKeluarga)
                        ->first();
                    if ($keluarga && !empty($keluarga->nama_keluarga)) {
                        return 'Wali: ' . $keluarga->nama_keluarga;
                    }
                }
            } catch (\Throwable $e) {
                // Abaikan jika tabel belum siap
            }
        }

        return 'Wali Siswa';
    }

    /**
     * Fallback accessor untuk paket belajar.
     */
    public function getPaketBelajarAttribute(?string $value): string
    {
        if (!empty($value)) {
            return $value;
        }

        return 'Gold · ' . $this->total_sesi . ' sesi';
    }

    /**
     * Fallback accessor untuk total sesi.
     */
    public function getTotalSesiAttribute($value): int
    {
        return $value !== null ? (int) $value : 8;
    }

    /**
     * Fallback accessor untuk sesi terpakai.
     */
    public function getSesiTerpakaiAttribute($value): int
    {
        return $value !== null ? (int) $value : 0;
    }

    /**
     * Sisa sesi yang belum dipakai.
     */
    public function getSisaSesiAttribute(): int
    {
        return max(0, $this->total_sesi - $this->sesi_terpakai);
    }

    /**
     * Persentase pemakaian sesi.
     */
    public function getPersentaseAttribute(): float
    {
        if ($this->total_sesi <= 0) {
            return 0;
        }

        return min(100, round(($this->sesi_terpakai / $this->total_sesi) * 100, 1));
    }
}
