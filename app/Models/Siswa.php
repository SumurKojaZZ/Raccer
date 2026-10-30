<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nama_wali',
        'paket_belajar',
        'total_sesi',
        'sesi_terpakai',
        'status_paket',
    ];

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

